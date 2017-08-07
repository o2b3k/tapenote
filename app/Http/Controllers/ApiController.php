<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Country;
use App\Models\Monument;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function countryList()
    {
        return Country::all();
    }


    public function mainCategories(Request $request, $countryId)
    {
        $country = Country::find($countryId);
        if ($country) {
            return Category::where('country_id', $country->id)->whereNull('parent_id')->get();
        } else {
            abort(404);
        }
    }


    public function subcategories(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);
        if ($category && $category->type == Category::TYPE_PARENT_CATEGORY) {
            return $category->children;
        } else {
            abort(404);
        }
    }


    public function category(Request $request, $categoryId) {
        $category = Category::find($categoryId);
        if ($category) {
            return $category;
        } else {
            abort(404);
        }
    }

    public function monumentsList(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);
        if ($category && $category->type == Category::TYPE_CATEGORY) {
            return $category->monuments;
        } else {
            abort(404);
        }
    }

    public function monument(Request $request, $id)
    {
        $monument = Monument::find($id);
        if ($monument) {
            $monument->photos;
            return $monument;
        } else {
            abort(404);
        }
    }
}