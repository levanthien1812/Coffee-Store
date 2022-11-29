<?php

use App\Http\Controllers\Cart;
use App\Http\Controllers\Menu;
use App\Http\Controllers\Order;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',function(){
    try {
        DB::connection()->getPdo();
        if(DB::connection()->getDatabaseName()){
            echo "Yes! Successfully connected to the DB: " . DB::connection()->getDatabaseName();
        }else{
            die("Could not find the database. Please check your configuration.");
        }
    } catch (\Exception $e) {
        die("Could not open connection to database server.  Please check your configuration.");
    }
});

// Card routes
Route::controller(Cart::class)
    ->prefix('cart')
    ->group(function () {
        Route::post('/', 'create');
        Route::get('/{id}', 'getCartsByCustomer');
        Route::delete('/{id}', 'deleteCart');
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
    ->middleware(['authen'])
    ->group(function () {
        Route::get('/signup', 'userSignup');
        Route::get('/login', 'userGetLogin');
        Route::post('/login', 'userPostLogin');
        Route::get('/', 'getAllUser');
    });
