<?php

use App\Http\Controllers\Card;
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
// Card routes
Route::controller(Card::class)
    ->prefix('card')
    ->group(function () {
        Route::post('/', 'create');
        Route::get('/{id}', 'getCardsByCustomer');
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
    ->middleware(['authen'])
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
        Route::get('/signup', [User::class, 'userSignup']);
        Route::get('/login', [User::class, 'userGetLogin']);
        Route::post('/login', [User::class, 'userPostLogin']);
        Route::get('/', [User::class, 'getAllUser']);
    });
