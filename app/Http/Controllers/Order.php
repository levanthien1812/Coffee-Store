<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Order extends Controller
{
    function getAllOrders(Request $request) {
        try {
            $ordersDB = DB::table('receipts')->join('detail_receipts', 'receipts.id', '=', 'detail_receipts.ReceiptID')->get();
            if ($ordersDB)
                return response()->json([
                    'message' => 'Get all orders successfully!',
                    'quantity' => $ordersDB->count(),
                    'data' => $ordersDB
                ]);
            else
                return response()->json([
                    'message' => 'No order.',
                ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    function getOrdersByCustomer(Request $request) {
        try {
            $customerID = $request->id;
            //ktra user
            $customer = DB::table('users')->where('id', $customerID)->get();
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found!'
                ]);
            }
            //get all orders
            $ordersDB = DB::table('receipts')
                ->where('CustomerID', $customerID)
                ->get();

            for($i = 0; $i < count($ordersDB); $i++){
                $detailOrder = DB::table('detail_receipts')->where('ReceiptID', $ordersDB[$i]->id)->get();
                for($j = 0; $j < count($detailOrder); $j++){
                    $items = DB::table('items')->where('id', $detailOrder[$j]->ItemID)->first(["Name", "Image"]);
                    $detailOrder[$j]->Item = $items;
                }
                $ordersDB[$i]->DetailOrder = $detailOrder;
            }

            if ($ordersDB)
                return response()->json([
                    'Message' => 'Get orders by customer successfully!',
                    'Orders' => $ordersDB
                ]);
            else
                return response()->json([
                    'message' => 'Customer doesn\'t have any orders'
                ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    function getOrderByID(Request $request) {
        try {
            $receiptID = $request->id;
            $receipt = DB::table('receipts')->where('id', $receiptID)->first();
            if (!$receipt)
                return response()->json([
                    'message' => 'Receipt not found!'
                ]);
                
            $detailReceipt = DB::table('detail_receipts')
                ->where('ReceiptID', $receiptID)
                ->get();

            for($i = 0; $i < count($detailReceipt); $i++){
                $item = DB::table('items')->where('id', $detailReceipt[$i]->ItemID)->first(["Name", "Image"]);
                $detailReceipt[$i]->Item = $item;
            }

            $receipt->DetailOrder = $detailReceipt;

            return response()->json([
                'Message' => 'Get Order By ID Success',
                'Orders' => $receipt
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
    function create(Request $request) {
        try {
            $orderReq = json_decode($request->getContent());
            $receiptReq = [
                'CustomerType' => $orderReq->CustomerType,
                'CustomerID' => $orderReq->CustomerID,
                'PhoneNumber' => $orderReq->PhoneNumber,
                'Address' => $orderReq->Address,
                'TotalAmount' => $orderReq->TotalAmount,
                'created_at' => now(),   
                'updated_at' => now(),
            ];

            if (!$orderReq) throw 'Fail to get request\' body';

            $newReceiptID = DB::table('receipts')->insertGetId($receiptReq);
            if (!$newReceiptID) 
                throw 'Fail to insert Receipt';
            
            foreach ($orderReq->Items as $Item) {
                // $Item = DB:table('items')->where('id', $ItemReq);
                $newDetailRec = DB::table('detail_receipts')->insert([
                    'ItemID' => $Item->ID,
                    'ReceiptID' => $newReceiptID,
                    'Quantity' => $Item->Quantity,
                    'Size' => $Item->Size,
                    'Price' => $Item->Price,
                   
                ]);
                if(!$newDetailRec) {
                    throw 'Fail to insert Detail receipt';
                }
            }

            return response()->json([
                'message' => 'Create Order successfully',
                'data' => $newReceiptID
            ]);
        } catch (Throwable $e) {
            error_log('Some message here.'.$e);
            return response()->json([
                'message error' => $e
            ]);
        }
    }

}
