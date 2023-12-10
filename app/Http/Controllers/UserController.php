<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function insert_user(Request $req)
    {

        $validatedData = $req->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'user_type' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required',
            'password' => 'required',

        ]);
        // 'password' => 'required|min:8',

        $user = new User;
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->usertype_id = $validatedData['user_type'];
        $user->email = $validatedData['email'];
        $user->phone = $validatedData['phone'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();
        return 
        response()->json([
            "Success" => true,
            "message" => "Registration Successfull"

        ]);

        // $user=User::insert([
        //     'first_name'=>$req->first_name,
        //     'last_name'=>$req->last_name,
        //     'user_type'=>$validatedData['user_type'],
        //     'email'=>$req->email,
        //     'phone'=>$req->phone,
        //     'password'=>$req->password ,



        // ]);

    }
}
