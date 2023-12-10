<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{


    public function insert_product(Request $req)
    {
        $validatedData = $req->validate([
            'product_nme' => 'required|max:255',
            'description' => 'required',
            'is_discount' => '',
            'image' => 'required',
            'user_id' => 'required',
            'price' => 'required',



        ]);
        // 'password' => 'required|min:8',

        $product = new Product;
        $product->product_nme = $validatedData['product_nme'];
        $product->description = $validatedData['description'];
        $product->is_discount = $validatedData['is_discount'];
        $product->image = $validatedData['image'];
        $product->price = $validatedData['price'];

        $product->slug = str_replace(' ', '-', $validatedData['product_nme']);
        $product->user_id = $validatedData['user_id'];

        $product->save();
        return
            response()->json([
                "Success" => true,
                "message" => "Product Added Successfully"

            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $req)
    {
        $validatedData = $req->validate([
            'product_id' => 'required',
            'product_nme' => 'required|max:255',
            'description' => 'required',
            'is_discount' => '',
            'image' => 'required',

            'price' => 'required',



        ]);
        // 'password' => 'required|min:8',




        $product_id = $req->product_id;
        $product = Product::find($product_id);
        $product->product_nme = $validatedData['product_nme'];
        $product->description = $validatedData['description'];
        $product->is_discount = $validatedData['is_discount'];
        $product->image = $validatedData['image'];
        $product->price = $validatedData['price'];

        $product->slug = str_replace(' ', '-', $validatedData['product_nme']);



        $product->save();
        return
            response()->json([
                "Success" => true,
                "message" => "Product Updated Successfully",
                "product" => $product

            ]);
    }


    public function delete(Request $req)

    {
        $validatedData = $req->validate([
            'product_id' => 'required',
           



        ]);
        $product_id = $req->product_id;
        $product = Product::find($product_id);
        $product->delete();
        return
        response()->json([
            "Success" => true,
            "message" => "Product Deleted Successfully",
            "product" => $product

        ]);
        //
    }

    public function get_products()

    {
        // $users = User::with('posts')->get();

        // $products = Product::all();
                $products = Product::with('users')->get();

                $results = DB::table('products')
->join('users', 'users.id', '=', 'products.user_id')
->select('products.*', 'users.first_name AS Owner')
->get();

        return response()->json([
            "products" => $results
        ]);
    }
}
