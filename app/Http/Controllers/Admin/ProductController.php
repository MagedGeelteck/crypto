<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use App\Models\ProductImage;

class ProductController extends Controller
{
    public function index()
    {
        $pageTitle = 'All Products';
        $products = Product::searchable(['name', 'category:name', 'subcategory:name'])->with(['category', 'subcategory'])->orderBy('id', 'DESC')->paginate(getPaginate());
        return view('admin.product.index', compact('pageTitle', 'products'));
    }

    public function create()
    {
        $pageTitle = "Add New Product";
        $categories = Category::active()->get();
        $subcategories = Category::active()->get();
        return view('admin.product.create', compact('pageTitle', 'categories', 'subcategories'));
    }

    public function update($id)
    {
        $pageTitle = "Update Product";
        $product = Product::findOrFail($id);
        $categories = Category::active()->get();
        $subcategories = Category::active()->get();

        $images = [];
        foreach ($product->images as $key => $image) {
            $img['id']  = $image->id;
            $img['src'] = getImage(getFilePath('product') . '/' . $image->name);
            $images[]   = $img;
        }

        return view('admin.product.create', compact('pageTitle', 'categories', 'subcategories', 'product', 'images'));
    }

    public function store(Request $request, $id = null)
    {
        $imageValidation = $id ? 'nullable' : 'required';

        $request->validate([
            'category_id'       => 'required|integer|gt:0',
            'sub_category_id'   => 'required|integer|gt:0',
            'name'              => 'required|max:255',
            'short_description' => 'required',
            'old_price'         => 'nullable|numeric|gt:0',
            'new_price'         => 'required|numeric|gt:0',
            'image'             => [$imageValidation, 'array', 'min:1'],
            'image.*'           => [$imageValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])],
            'product_file' => ['nullable', new FileTypeValidate(['zip', 'txt', 'pdf'])],
        ]);


        if (!$id) {
            $notification = 'Product added successfully';
            $product         = new Product();
        } else {
            $notification = 'Product updated successfully';
            $product         = Product::findOrFail($id);
        }

        if ($request->hasFile('product_file')) {
            try {
                $old = $product->product_file;
                $product->product_file = fileUploader($request->product_file, getFilePath('productFile'), null, $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your file'];
                return back()->withNotify($notify);
            }
        }

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->old_price = $request->old_price;
        $product->new_price = $request->new_price;
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->save();

        $image = $this->insertImages($request, $product, $id);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'message' => "Couldn\'t upload product images",
            ]);
        }

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }


    public function status($id)
    {
        return Product::changeStatus($id);
    }

    public function feature($id)
    {
        return Product::changeStatus($id, 'featured');
    }

    protected function insertImages($request, $product, $id = null)
    {
        $path = getFilePath('product');
        if ($id) {
            $this->removeImages($request, $product, $path);
        }

        $hasImages = $request->file('image');

        if ($hasImages) {
            $size      = getFileSize('product');
            $images    = [];

            foreach ($hasImages as $file) {
                try {
                    $name              = fileUploader($file, $path, $size, null, null);
                    $image             = new ProductImage();
                    $image->product_id = $product->id;
                    $image->name       = $name;
                    $images[]          = $image;
                } catch (\Exception $exp) {
                    return false;
                }
            }
            $product->images()->saveMany($images);
        }
        return true;
    }

    protected function removeImages($request, $product, $path)
    {
        $previousImages = $product->images->pluck('id')->toArray();
        $imageToRemove  = array_values(array_diff($previousImages, $request->old ?? []));
        foreach ($imageToRemove as $item) {
            $productImage   = ProductImage::find($item);
            fileManager()->removeFile($path . '/' . $productImage->name);
            $productImage->delete();
        }
    }
}
