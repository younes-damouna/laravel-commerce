<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartItemController extends Controller
{

    /**
     * Show the form for creating a new resource.
     */
    public function add_to_cart(Request $req)

    {
        // get product price from products table
        $product = Product::where('id', $req->product_id)->first();

        // $cart_item->user_id = $req->user_id;
        //get cart item data from product
        $cart_item = new CartItem;

        $cart_item->quantity = $req->quantity;
        $cart_item->sub_total = $req->quantity * $product->price;
        $cart_item->product_id = $product->id;
        $cart_id = "";
        // Check if user already have a cart


        $existing_cart = Cart::where('user_id', $req->user_id)->first();
        if ($existing_cart) {
            $cart_id = $existing_cart->id;
        } else {
            $cart = new Cart;
            $cart->user_id = $req->user_id;


            $cart->save();
            $cart_id = $cart->id;
            echo $cart_id;
        }

        // check if the user already added the product to cartitems
        $new_quantity = "";
        $existing_product = CartItem::where('cart_id', $cart_id)->where('product_id', $req->product_id)->first();


        if ($existing_product) {


            $new_quantity = $existing_product->quantity + $req->quantity;

            $new_sub_total = $existing_product->sub_total = $new_quantity * $product->price;
            $existing_product->cart_id = $cart_id;
            $existing_product->quantity = $new_quantity;
            $existing_product->sub_total = $new_sub_total;
            // $cart->total = $cart->cart_total+$new_sub_total;
            // $cart->save();
            $existing_product->save();
        } else {
            // $cart->total = $cart->cart_total;
            // $cart->save();
            $cart_item->cart_id = $cart_id;

            $cart_item->save();
        }








        return
            response()->json([
                "Success" => $new_quantity,
                "message" => "Product Added Successfully to cart"

            ]);
    }

    public function remove_cart_item(Request $req)
    {
        $product_id = $req->product_id;
        $cart_id = $req->cart_id;
        $cart_item = CartItem::where('product_id', $product_id)->where('cart_id', $cart_id)->first();

        $cart_item->delete();
        return
            response()->json([
                "Success" => true,
                "message" => "Product Removed From Card Successfully",
                // "product" => $product

            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function get_user_cart(Request $req)
    {
        //

        $results = DB::table('carts')
            ->join('cart_items', 'carts.id', '=', 'cart_items.cart_id')
            ->join('products', 'products.id', '=', 'cart_items.product_id')->where('carts.user_id', $req->user_id)

            ->select('products.*', 'cart_items.sub_total', 'cart_items.quantity')
            // ->groupBy('carts.id')
            ->get();
        // dd($results);

        return response()->json([
            "products" => $results
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
}
