<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // id	order_id	created_at	updated_at	status	user_id
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function add_transaction(Request $req)
    {
        $user = Auth::user();
        $transaction = new Transaction;
        $transaction->order_id = $req->order_id;
       
        $transaction->user_id = $user->id;
        // dd($user->id);

        $transaction->save();
        return
            response()->json([
                "Success" => true,
                "message" => "Transaction Added Successfully"

            ]);
    
    }

    public function get_transactions(Request $req)
    {
        $user = Auth::user();

        $results = DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->join('transactions','transactions.order_id','=','orders.id')->where('orders.user_id', $user->id)

            ->select('products.*', 'order_items.sub_total', 'order_items.quantity',"status as transaction_status")
            // ->groupBy('orders.id')
            ->get();
        // dd($results);

        return response()->json([
            "Transactions" => $results
        ]);

    
    }
    public function update_transactions(Request $req)
    {
        $user = Auth::user();
        $transaction =Transaction::find($req->order_id);
        $transaction->order_id = $req->order_id;
       
        $transaction->user_id = $user->id;
        $transaction->status = $req->status;
        // dd($user->id);

        $transaction->save();
        return
            response()->json([
                "Success" => true,
                "message" => "Transaction Updated Successfully"

            ]);
    
    }
}