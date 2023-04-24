<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request){
        
        $keyword = $request->get('search');
        $perPage = 5;

        if (!empty($keyword)) {
            $products = Product::where('name', 'LIKE', "%$keyword%")
                ->orWhere('category', 'LIKE', "%$keyword%")

                ->latest()->paginate($perPage);
        } else {
            $products = Product::latest()->paginate($perPage);
        }
        return view('products.index',['products' => $products])->with('i', (request()->input('page', 1) - 1) *5);
    }

    public function create()
    {
        return view('products.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2028',
            'category' => 'required',
            'quantity' => 'required',
            'price' => 'required',
        ]);

        $product = new Product;

        $file_name = time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images'), $file_name);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->image = $file_name;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->price = $request->price;

        $product->save();
        return redirect()->route('products.index')->with('success','Product created successfully.');
    }

    // function for edit product
    public function edit($id){
        $product = Product::findOrfail($id);
        return view('products.edit', ['product' => $product]);
    }

    // function for update product
    public function update(Request $request, Product $product){
        $request->validate([
            'name' => 'required',
        ]);

        $file_name = $request->hidden_product_image;

        if($request->image != ''){
            $file_name = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $file_name);
        }

        $product = Product::find($request->hidden_id);

        $product->name = $request->name;
        $product->description = $request->description;
        $product->image = $file_name;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->price = $request->price;

        $product->save();
        return redirect()->route('products.index')->with('success','Product updated successfully.');
    }

    // function for destory product
    public function destroy($id){
        $product = Product::findOrfail($id);
        $image_path = public_path()."/images/";
        $image = $image_path. $product->image;
        if(file_exists($image)){
            @unlink($image);
        }
        $product->delete();
        return redirect('products')->with('success','Product deleted successfully.');
    }
}
