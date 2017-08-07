<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request, $countryId = null)
    {
        $country = null;
        if ($countryId != null) {
            $country = Country::find($countryId);
        }

        if ($country != null) {
            return view('categories.index', [
                'categories' => Category::where('country_id', $country->id)->whereNull('parent_id')->get(),
                'country' => $country
            ]);
        }

        return view('categories.index', ['categories' => Category::whereNull('parent_id')->get()]);
    }


    public function addForm()
    {
        return view('categories.add', ['countries' => Country::all()]);
    }


    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getAddValidationRules(false));
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $country = Country::find($request->input('country_id'));
        $category = new Category();

        return $this->saveCategory($request, $category, $country);
    }


    public function view(Request $request, $id)
    {
        $category = $this->getValidatedCategory($id);
        return view('categories.view', ['category' => $category]);
    }


    public function addChildForm(Request $request, $parentId)
    {
        $parent = $this->getValidatedCategory($parentId);
        return view('categories.add', ['parent' => $parent]);
    }


    public function addChild(Request $request, $parentId)
    {
        $parentCategory = $this->getValidatedCategory($parentId);
        $validator = Validator::make($request->all(), $this->getAddValidationRules(true));
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $country = $parentCategory->country;
        $category = new Category();

        $category->parent()->associate($parentCategory);
        return $this->saveCategory($request, $category, $country, $parentId);
    }


    public function editForm(Request $request, $id)
    {
        $category = $this->getValidatedCategory($id);
        $data = [
            'category' => $category,
        ];
        if ($category->parent == null) {
            $data['countries'] = Country::all();
        }
        return view('categories.edit', $data);
    }


    public function edit(Request $request, $id)
    {
        $category = $this->getValidatedCategory($id);
        $validator = Validator::make($request->all(), $this->getAddValidationRules($category->parent != null));
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $country = null;
        if ($category->parent == null) {
            $country = Country::find($request->input('country_id'));
        } else {
            $country = $category->parent->country;
        }

        return $this->saveCategory($request, $category, $country);
    }

    private function saveCategory(Request $request, Category $category, Country $country, $parentId = null)
    {
        $categoryType = $request->input('type');
        if (($category->children->count() > 0 && $categoryType != Category::TYPE_PARENT_CATEGORY)
            || $category->monuments->count() > 0 && $categoryType != Category::TYPE_CATEGORY
        ) {
            if ($category->children->count() > 0) {
                $message = 'Can not change category type. The category has subcategories. Delete subcategories to perform this action';
            } else {
                $message = 'Can not change category type. The category has monuments. Delete monuments to perform this action';
            }

            $request->session()->flash('status', [
                'type' => 'error',
                'title' => 'Error',
                'message' => $message
            ]);

            return redirect()->back()->withInput();
        }

        if ($category->type == Category::TYPE_WEB_IMAGE) {
            if (file_exists($category->data) && !unlink($category->data)) {
                $request->session()->flash('status', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Unable delete old image of category'
                ]);

                return redirect()->back()->withInput();
            }
        }

        switch ($categoryType) {
            case Category::TYPE_EXTERNAL_LINK:
            case Category::TYPE_INTERNAL_LINK:
                $validator = Validator::make($request->all(), ['data' => 'required|url']);
                break;

            case Category::TYPE_WEB_CONTENT:
                $validator = Validator::make($request->all(), ['data' => 'required']);
                break;

            case Category::TYPE_WEB_IMAGE:
                $validator = Validator::make($request->all(), [
                    'data_image' => 'required|file|image|max:2048|mimes:jpg,jpeg,png'
                ]);
                break;

            default:
                $validator = null;
        }

        if ($validator != null) {
            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            if ($categoryType == Category::TYPE_WEB_IMAGE) {
                $image = $request->file('data_image');

                $fileName = md5(time()) . '.' . $image->getClientOriginalExtension();
                $path = $image->move('uploads/web-images', $fileName);

                if (!$path) {
                    $request->session()->flash('status', [
                        'type' => 'success',
                        'title' => 'Success',
                        'message' => 'Category information saved successfully'
                    ]);

                    return redirect()->back()->withInput()->withErrors($validator->errors());
                }

                $category->data = $path;
            } else {
                $category->data = $request->input('data');
            }
        }

        $category->name = $request->input('name');
        $category->type = $categoryType;
        $category->country_id = $country->id;

        $category->save();
        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Category information saved successfully'
        ]);

        if ($parentId) {
            return redirect()->route('categories.view', ['id' => $parentId]);
        } else if ($category->parent != null) {
            return redirect()->route('categories.view', ['id' => $category->parent->id]);
        } else {
            return redirect()->route('categories.index');
        }
    }


    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $category = $this->getValidatedCategory($request->input('category_id'));
        if ($category->children && $category->children->count() > 0) {
            $request->session()->flash('status', [
                'type' => 'success',
                'title' => 'Error',
                'message' => 'Can not delete category which has child categories.'
            ]);
            return redirect()->back();
        }

        if ($category->type == Category::TYPE_WEB_IMAGE) {
            if (file_exists($category->data) && !unlink($category->data)) {
                $request->session()->flash('status', [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Unable delete old image of category'
                ]);

                return redirect()->back();
            }
        }

        $parent = $category->parent;
        $category->delete();
        $request->session()->flash('status', [
            'type' => 'success',
            'title' => 'Success',
            'message' => 'Category deleted successfully'
        ]);

        if ($parent != null) {
            return redirect()->route('categories.view', ['id' => $parent->id]);
        } else {
            return redirect()->route('categories.index');
        }
    }


    private function getAddValidationRules($hasParent = false)
    {
        $rules = [
            'name' => ['required', 'max:100'],
            'type' => [
                'required',
                Rule::in(Category::getCategories(false))
            ],
            'data' => [],
        ];

        if (!$hasParent) {
            $rules['country_id'] = ['required', 'exists:countries,id'];
        }

        return $rules;
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