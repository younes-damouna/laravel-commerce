<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }



    public function insert_product(Request $req)
    {
        $user = Auth::user();
        // dd($user->usertype_id);
        $validatedData = $req->validate([
            'product_nme' => 'required|max:255',
            'description' => 'required',
            'is_discount' => '',
            'image' => 'required',
            // 'user_id' => 'required',
            'price' => 'required',



        ]);
        // 'password' => 'required|min:8',
        if($user->usertype_id==1){
            $product = new Product;
            $product->product_nme = $validatedData['product_nme'];
            $product->description = $validatedData['description'];
            $product->is_discount = $validatedData['is_discount'];
            $product->image = $validatedData['image'];
            $product->price = $validatedData['price'];
    
            $product->slug = str_replace(' ', '-', $validatedData['product_nme']);
            $product->user_id = $user->id;
            // dd($user->id);
    
            $product->save();
            return
                response()->json([
                    "Success" => true,
                    "message" => "Product Added Successfully"
    
                ]);
        }else{
            return
            response()->json([
                "Success" => false,
                "message" => "Not Authorized",


            ]);
        }
      
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
        // dd($validatedData);
        $product->product_nme = $validatedData['product_nme'];
        $product->description = $validatedData['description'];
        $product->is_discount = $validatedData['is_discount'];
        $product->image = $validatedData['image'];
        $product->price = $validatedData['price'];

        $product->slug = str_replace(' ', '-', $validatedData['product_nme']);
        $user = Auth::user();
        if (Gate::allows('update_product', $product)) {
            // abort(403);
            $product->save();
            return
                response()->json([
                    "Success" => true,
                    "message" => "Product Updated Successfully",
                    "product" => $product

                ]);
        } else {

            return
                response()->json([
                    "Success" => false,
                    "message" => "Not Authorized",


                ]);
        }
    }


    public function delete(Request $req)

    {
        $validatedData = $req->validate([
            'product_id' => 'required',




        ]);

        $product_id = $req->product_id;
        $product = Product::find($product_id);
        $user = Auth::user();
        if (Gate::allows('update_product', $product)) {
            // abort(403);
            $product->delete();
            return
                response()->json([
                    "Success" => true,
                    "message" => "Product Deleted Successfully",
                    "product" => $product
    
                ]);
        } else {

            return
                response()->json([
                    "Success" => false,
                    "message" => "Not Authorized",


                ]);
        }



       
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
