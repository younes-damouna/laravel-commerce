<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function add_to_order(Request $req)

    {   $user = Auth::user();
        // get product price from products table
        $product = Product::where('id', $req->product_id)->first();

        // $order_item->user_id = $req->user_id;
        //get order item data from product
        $order_item = new orderItem;

        $order_item->quantity = $req->quantity;
        $sub_total = $req->quantity * $product->price;
        $order_item->sub_total=$sub_total;
       
        $order_item->product_id = $product->id;
        $order_id = "";
        // $order_item->address_id=1;
        // Check if user already have a order


        $existing_order = Order::where('user_id',$user->id)->first();
        // dd($user);
        if ($existing_order) {
            $order_id = $existing_order->id;
        } else {
            $order = new order;
            $order->user_id = $user->id;
            $order->address_id=1;
            $old_total=$order->total+$sub_total;
            $order->total=$old_total;
            // dd($user);
            $order->save();
            $order_id = $order->id;
            // echo $order_id;
        }

        // check if the user already added the product to orderitems
        $new_quantity = "";
        $existing_product = OrderItem::where('order_id', $order_id)->where('product_id', $req->product_id)->first();


        if ($existing_product) {


            $new_quantity = $existing_product->quantity + $req->quantity;

            $new_sub_total = $existing_product->sub_total = $new_quantity * $product->price;
            $existing_product->order_id = $order_id;
            $existing_product->quantity = $new_quantity;
            $existing_product->sub_total = $new_sub_total;
            // $order->total = $order->order_total+$new_sub_total;
            // $order->save();
            $existing_product->save();
        } else {
            // $order->total = $order->order_total;
            // $order->save();
            $order_item->order_id = $order_id;

            $order_item->save();
        }








        return
            response()->json([
                "Success" => $new_quantity,
                "message" => "Product Added Successfully to order"

            ]);
    }

    public function remove_order_item(Request $req)
    {
        $product_id = $req->product_id;
        $order_id = $req->order_id;
        $order_item = orderItem::where('product_id', $product_id)->where('order_id', $order_id)->first();

        $order_item->delete();
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
    public function get_user_order(Request $req)
    {
        //
        $user = Auth::user();

        $results = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')->where('orders.user_id', $user->id)

            ->select('products.*', 'order_items.sub_total', 'order_items.quantity')
            // ->groupBy('orders.id')
            ->get();
        // dd($results);

        return response()->json([
            "Orders" => $results
        ]);
    }
}
