<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Category;

use Illuminate\Http\Request;


class Menu extends Controller
{
    function getBestSeller(){
        $itemsDB = Item::with('category')->skip(0)->take(10)->get();;

        return response()->json([
            'message' => 'Get Bestseller',
            'data' => $itemsDB
        ]);
    }
    function getAllItems () {
        $itemsDB = Item::with('category')->get();

        return response()->json([
            'message' => 'Get All Products',
            'data' => $itemsDB
        ]);
    }

    /**
     * Get Product
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  unsignedBigInteger  $id
     * @return \Illuminate\Http\Response
     */

    function getItemByID (Request $request, $id) {
        $itemDB = Item::with('category')->findOrFail($id);

        if($itemDB){
            return response()->json([
                'message' => 'Get Product',
                'data' => $itemDB
            ]);
        }else{
            return response()->json([
                'message' => 'Product invalid'
            ]);
        }
    }

    /**
     * Get By Category
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  unsignedBigInteger  $id
     * @return \Illuminate\Http\Response
     */

    function getItemsByCategory (Request $request, $id) {
        $itemsDB = Item::with('category')->where('Type', $id)->get();

        return response()->json([
            'message' => 'Get Products By Category',
            'data' => $itemsDB
        ]);
    }

    function getAllCategories() {
        $categoriesDB = Category::all();

        return response()->json([
            'message' => 'Get All Category',
            'data' => $categoriesDB
        ]);
    }
}
