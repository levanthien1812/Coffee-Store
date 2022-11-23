<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Cart extends Controller
{
    function create (Request $req) {
        $cartFields = $req->validate([
            'itemID' => 'required',
            'quantity' => 'required',
            'size' => 'required',
            'price' => 'required',
            'customerID' => 'required'
        ]);

        return response()->json([
            'data' => $cartFields
        ]);

        $customer = DB::table('user')->where('id', $req->customerID)->get();
        return response()->json([
            'data' => $customer
        ]);
    }
}
