<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Item;
use App\Models\User;
use App\Models\Cart as CartModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cart extends Controller
{
    function create(Request $req)
    {
        try {
            $cartFields = json_decode($req->getContent());

            $customer = DB::table('users')->where('ID', $cartFields->CustomerID)->first();
            if (!$customer) {
                return 'Customer not found!';
            }

            $cartItem = DB::table('carts')->where([
                ['CustomerID', $cartFields->CustomerID],
                ['ItemID', $cartFields->ItemID],
                ['Size', $cartFields->Size]
            ])->first();

            $updatedCart = null;
            if ($cartItem) {
                $updatedCart = DB::table('carts')->where('id', $cartItem->id)->update([
                    'Quantity' => $cartItem->Quantity + $cartFields->Quantity,
                    'Price' => $cartItem->Price + $cartFields->Price
                ]);
            } else {
                $newCart = DB::table('carts')->insert([
                    'ItemID' => $cartFields->ItemID,
                    'Quantity' => $cartFields->Quantity,
                    'Size' => $cartFields->Size,
                    'Price' => $cartFields->Price,
                    'CustomerID' => $cartFields->CustomerID,
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

    function deleteCart(Request $req){
        try{
            $listIds = $req['IDs'];
            for($i=0; $i < count($listIds); $i++){
                CartModel::where('id', $listIds[$i])->delete();
            }
            return response()->json([
                'message' => 'deleted'
            ]);
        }catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
        
    }

    function getCartsByCustomer(Request $request)
    {
        try {
            $customerID = $request['id'];
            $cartsDB = DB::table('carts')->where('customerID', $customerID)->get();


            for($i = 0; $i < count($cartsDB); $i++){
                $item = DB::table('items')->where('id', $cartsDB[$i]->ItemID)->first(["Name", "Image"]);
                $cartsDB[$i]->Item = $item;
            }

            //$cartsDB = CartModel::join('items', 'items.id', '=', 'carts.ItemID')->get();


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

    // function deleteCart(Request $request)
    // {
    //     try {
    //         $cartDB = DB::table('carts')->where('id', $request['id'])->delete();
    //         if (!$cartDB) {
    //             throw 'Cart does not exist!';
    //         }
    //         return response()->json([
    //             'message' => 'Card deleted'
    //         ]);
    //     } catch (Throwable $e) {
    //         return response()->json([
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    // }
}
