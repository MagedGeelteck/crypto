<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    public function index($id)
    {
        $category = Category::findOrFail($id);
        $pageTitle = $category->name . ' - Subcategories';
        $subcategories = SubCategory::searchable(['name'])->where('category_id', $category->id)->paginate(getPaginate());
        return view('admin.category.subcategory', compact('pageTitle', 'subcategories', 'category'));
    }

    public function store(Request $request, $category, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:40'
        ]);

        $category = Category::find($category);

        if (!$category) {
            $notify[] = ['error', 'Category not found'];
            return back()->withNotify($notify);
        }

        if (!$id) {
            $notification = 'Subcategory added successfully';
            $subcategory         = new SubCategory();
        } else {
            $notification = 'Subcategory updated successfully';
            $subcategory         = SubCategory::findOrFail($id);
        }

        $subcategory->category_id = $category->id;
        $subcategory->name = $request->name;
        $subcategory->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }


    public function status($id)
    {
        return SubCategory::changeStatus($id);
    }
}
