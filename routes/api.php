<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::controller(AuthController::class)->group(
    // [
    // 'middleware' => 'api',
    // 'prefix' => 'auth'
    // ]
    // ,
    function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');

});
// Route::post('/register',[UserController::class,'insert_user']);
Route::post('/products/add',[ProductController::class,'insert_product']);
Route::post('/products/edit',[ProductController::class,'update']);
Route::get('/products',[ProductController::class,'get_products']);
Route::post('/products/delete',[ProductController::class,'delete']);

Route::post('/carts/add',[CartItemController::class,'add_to_cart']);
Route::post('/carts/remove',[CartItemController::class,'remove_cart_item']);

Route::post('/carts/get_user_cart',[CartItemController::class,'get_user_cart']);












// Route::post('/login',[AuthController::class,'login']);
// Route::post('/register',[AuthController::class,'register']);
// Route::post('/logout',[AuthController::class,'logout']);
// Route::post('/refresh',[AuthController::class,'refresh']);
