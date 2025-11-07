<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Categories';
        $categories = Category::searchable(['name'])->paginate(getPaginate());
        return view('admin.category.index', compact('pageTitle', 'categories'));
    }

    public function store(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:40'
        ]);

        if (!$id) {
            $notification = 'Category added successfully';
            $category         = new Category();
        } else {
            $notification = 'Category updated successfully';
            $category         = Category::findOrFail($id);
        }
        $category->name = $request->name;
        $category->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }


    public function status($id)
    {
        return Category::changeStatus($id);
    }
}
