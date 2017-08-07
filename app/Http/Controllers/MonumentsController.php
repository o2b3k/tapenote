<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Monument;
use App\Models\Photo;
use Illuminate\Http\Request;
use Validator;

class MonumentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request, $categoryId = null)
    {
        $monuments = null;
        if ($categoryId) {
            $category = $this->getValidatedCategory($categoryId);
            if ($category->type != Category::TYPE_CATEGORY) {
                abort(404);
            }
        } else {
            $monuments = Monument::all();
        }

        return view('monuments.index', ['monuments' => $monuments]);
    }


    public function addForm(Request $request, $categoryId)
    {
        $category = $this->getValidatedCategory($categoryId);
        if ($category->type != Category::TYPE_CATEGORY) {
            abort(404);
        }

        return view('monuments.add', ['category' => $category]);
    }


    public function add(Request $request, $categoryId)
    {
        $category = $this->getValidatedCategory($categoryId);
        if ($category->type != Category::TYPE_CATEGORY) {
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Invalid category type'
            ]);

            return redirect()->back();
        }

        $validator = Validator::make($request->all(), $this->getAddValidationRules());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $monument = Monument::create([
            'name' => $request->input('name'),
            'area' => $request->input('area'),
            'data' => $request->input('data'),
            'category_id' => $category->id,
        ]);

        return redirect()->route('monuments.view', ['id' => $monument->id]);
    }


    public function view(Request $request, $id)
    {
        $monument = $this->getValidatedMonument($id);
        return view('monuments.view', ['monument' => $monument]);
    }


    public function editForm(Request $request, $id)
    {
        $monument = $this->getValidatedMonument($id);
        return view('monuments.add', ['monument' => $monument, 'category' => $monument->category]);
    }


    public function edit(Request $request, $id)
    {
        $monument = $this->getValidatedMonument($id);
        $validator = Validator::make($request->all(), $this->getAddValidationRules());
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $monument->name = $request->input('name');
        $monument->area = $request->input('area');
        $monument->data = $request->input('data');
        $monument->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Monument data successfully changed',
        ]);

        return redirect()->route('monuments.view', ['id' => $monument->id]);
    }


    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), ['monument_id' => 'required|exists:monuments,id']);
        if ($validator->fails()) {
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Monument not found',
            ]);

            return redirect()->back();
        }

        $monument = Monument::find($request->input('monument_id'));
        $category = $monument->category;
        foreach ($monument->photos as $photo) {
            if (file_exists($photo->path)) {
                unlink($photo->path);
            }

            if ($photo->big_image_path && file_exists($photo->big_image_path)) {
                unlink($photo->big_image_path);
            }

            $photo->delete();
        }

        $monument->delete();
        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Monument data successfully deleted',
        ]);

        return redirect()->route('categories.view', ['id' => $category->id]);
    }


    public function uploadImage(Request $request, $id)
    {
        $monument = $this->getValidatedMonument($id);
        $validator = Validator::make($request->all(), [
            'image' => 'required|file|image|max:1024|mimes:jpg,jpeg,png',
            'big_image' => 'file|image|max:2048|mimes:jpg,jpeg,png',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => $errors->first(),
            ]);

            return redirect()->back();
        }

        $image = $request->file('image');
        $size = getimagesize($image->path());
        if (!$size || count($size) < 2) {
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Could not read image size',
            ]);

            return redirect()->back();
        }

        $fileName = md5(time());
        $path = $image->move('uploads/images', $fileName . '.' . $image->getClientOriginalExtension());

        $photo = new Photo();
        $photo->path = $path;
        $photo->description = $request->input('description');
        $photo->width = $size[0];
        $photo->height = $size[1];
        $photo->monument()->associate($monument);

        if ($request->hasFile('big_image')) {
            $bigImage = $request->file('big_image');
            if (!$bigImage && !$bigImage->isValid()) {
                // Delete uploaded file
                if (file_exists($path)) {
                    unlink($path);
                }

                $request->session()->flash('status', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Large image file is not exists or is not valid'
                ]);

                return redirect()->back();
            }

            $bigImagePath = $bigImage->move(
                'uploads/images',
                $fileName . '_big.' . $bigImage->getClientOriginalExtension()
            );

            $photo->big_image_path = $bigImagePath;
        }

        $photo->save();

        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Photo successfully uploaded',
        ]);

        return redirect()->back();
    }


    public function deleteImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image_id' => 'required|exists:photos,id'
        ]);
        if ($validator->fails()) {
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => $validator->errors()->first(),
            ]);

            return redirect()->back();
        }

        $photo = Photo::find($request->input('image_id'));
        if (!$photo) {
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'File not found'
            ]);

            return redirect()->back();
        }

        if ($photo->big_image_path && file_exists($photo->big_image_path)) {
            unlink($photo->big_image_path);
        }

        if (file_exists($photo->path) && !unlink($photo->path)) {
            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => 'Could not delete file'
            ]);

            return redirect()->back();
        }

        $photo->delete();
        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'File has been deleted successfully'
        ]);

        return redirect()->back();
    }


    private function getAddValidationRules()
    {
        return [
            'name' => 'required|string|max:255',
            'area' => 'nullable|string|max:255',
            'data' => 'required|string',
        ];
    }

    private function getValidatedMonument($id)
    {
        if (!$id) {
            abort(404);
        }

        $monument = Monument::find($id);
        if (!$monument) {
            abort(404);
        }

        return $monument;
    }

    private function getValidatedCategory($id)
    {
        if (!$id) {
            abort(404);
        }

        $category = Category::find($id);
        if (!$category) {
            abort(404);
        }

        return $category;
    }
}