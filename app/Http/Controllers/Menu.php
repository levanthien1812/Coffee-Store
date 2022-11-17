<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Category;

use Illuminate\Http\Request;


class Menu extends Controller
{
    function getAllItems () {
        $itemsDB = Item::with('category')->get();

        return response()->json([
            'Message' => 'Get All Products',
            'Data' => $itemsDB
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
                'Message' => 'Get Product',
                'Data' => $itemDB
            ]);
        }else{
            return response()->json([
                'Message' => 'Product invalid'
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
            'Message' => 'Get Products By Category',
            'Data' => $itemsDB
        ]);
    }

    function getAllCategories() {
        $categoriesDB = Category::all();

        return response()->json([
            'Message' => 'Get All Category',
            'Data' => $categoriesDB
        ]);
    }
}
