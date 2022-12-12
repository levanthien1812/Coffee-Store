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
            $customer = DB::table('users')->where('id', $customerID)->get();
            if (!$customer) {
                return response()->json([
                    'message' => 'Customer not found!'
                ]);
            }
            $ordersDB = DB::table('receipts')
                ->join('detail_receipts', 'receipts.id', '=', 'detail_receipts.ReceiptID')
                ->where('CustomerID', $customerID)
                ->get();
            if ($ordersDB)
                return response()->json([
                    'message' => 'Get orders by customer successfully!',
                    'quantity' => $ordersDB->count(),
                    'data' => $ordersDB
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
            $receipt = DB::table('receipts')->where('id', $receiptID)->get();
            if (!$receipt)
                return response()->json([
                    'message' => 'Receipt not found!'
                ]);
                
            $detailReceipt = DB::table('receipts')
                ->join('detail_receipts', 'receipts.id', '=', 'detail_receipts.ReceiptID')
                ->where('receipts.id', $receiptID)
                ->get();

            return response()->json([
                'message' => 'Get order successfully',
                'data' => $detailReceipt
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
            ];

            if (!$orderReq) throw 'Fail to get request\' body';

            $newReceiptID = DB::table('receipts')->insertGetId($receiptReq);
            if (!$newReceiptID) 
                throw 'Fail to insert Receipt';
            
            foreach ($orderReq->Items as $Item) {
                // $Item = DB:table('items')->where('id', $ItemReq);
                $newDetailRec = DB::table('detail_receipts')->insert([
                    'ItemID' => $Item->id,
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
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

}
