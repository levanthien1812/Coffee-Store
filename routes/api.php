<?php

use App\Http\Controllers\Cart;
use App\Http\Controllers\Menu;
use App\Http\Controllers\Order;
use App\Http\Controllers\User;
use App\Http\Controllers\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//cart routes
Route::controller(Cart::class)
    ->prefix('cart')
    ->group(function () {
        Route::post('/', 'create');
        Route::get('/{id}', 'getCartsByCustomer');
        Route::delete('/', 'deleteCart');
    });

// Menu routes
Route::controller(Menu::class)
    ->prefix('menu')
    ->group(function () {
        Route::get('/products', 'getAllItems');
        Route::get('/products/{id}', 'getItemByID');
        Route::get('/categories', 'getAllCategories');
        Route::get('/categories/{id}', 'getItemsByCategory');
    });

// Order routes
Route::controller(Order::class)
    ->prefix('order')
    // ->middleware(['authen'])
    ->group(function () {
        Route::get('/', 'getAllOrders');
        Route::get('/customer/{id}', 'getOrdersByCustomer');
        Route::get('/{id}', 'getOrderByID');
        Route::post('/create', 'create');
    });

// User routes
Route::controller(User::class)
    ->prefix('user')
    ->group(function () {
        Route::post('/update', 'updateUser');
        Route::post('/signup', 'userSignup');
        Route::get('/login', 'userGetLogin');
        Route::post('/login', 'userPostLogin');
        Route::get('/', 'getAllUser');
    });

    // Menu routes

//blogs
Route::controller(Blog::class)
->prefix('blog')
->group(function () {
    Route::get('/', 'getAllBlogs');
    Route::get('/{id}', 'getBlogByID');
});


//Protected Route
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('user/logout', [User::class,'logout']);
});



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
