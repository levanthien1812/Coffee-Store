<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cart extends Controller
{
    function create(Request $request) {
        try {
            $cartFields = $request->validate([
                "itemID" => "required",
                "quantity" => "required",
                "size" => "required",
                "price" => "required",
                "customerID" => "required"
            ]);

            $customer = DB::table('users')->where('ID', $cartFields['customerID'])->first();
            if (!$customer) {
                return 'Customer not found!';
            }

            $cartItem = DB::table('carts')->where([
                ['CustomerID', $cartFields['customerID']],
                ['ItemID', $cartFields['itemID']],
                ['Size', $cartFields['size']]
            ])->first();

            $updatedCart = null;
            if ($cartItem) {
                $updatedCart = DB::table('carts')->where('id', $cartItem->id)->update([
                    'Quantity' => $cartItem->Quantity + $cartFields['quantity'],
                    'Price' => $cartItem->Price + $cartFields['price']
                ]);
            } else {
                $newCart = DB::table('carts')->insert([
                    'ItemID' => $cartFields['itemID'],
                    'Quantity' => $cartFields['quantity'],
                    'Size' => $cartFields['size'],
                    'Price' => $cartFields['price'],
                    'CustomerID' => $cartFields['customerID'],
                    'Status' => "InCart"
                ]);
                if (!$newCart) {
                    throw 'Can\'t create cart';
                } else {
                    $updatedCart = $newCart;
                }
            }

            return response()->json([
                'message' => 'Added to cart',
                'data' => $updatedCart
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    function getCartsByCustomer(Request $request) {
        try {
            $customerID = $request['id'];
            $cartsDB = DB::table('carts')->where('customerID', $customerID)->get();

            return response()->json([
                'message' => 'Get Cart By Customer\'s ID',
                'data' => $cartsDB
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    function deleteCart(Request $request) {
        try {
            $cartDB = DB::table('carts')->where('id', $request['id'])->delete();
            if(!$cartDB) {
                throw 'Cart does not exist!';
            }
            return response()->json([
                'message' => 'Card deleted'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
