<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $index = Order::all();
        return response()->json($index, 200);
    }


    public function create()
    {

    }

    public function store(Request $request)
    {
        // $order = Order::create($request->all());
        // return response()->json($order, 200);

        $request->validate([
            'product_id' => "required|integer",
            'quantity' => "required|integer"
        ]);
        $product = Product::find($request->product_id);
        if (empty($product)) {
            return response()->json(["message" => __("file kosong")], 404);

        }
        $price = $product->price;
        $totalprice = $price * ($request->quantity);

        $order = order::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalprice,
            'order_date' => now()

        ]);
        return response()->json($order, 200);
    }


    public function show($id)
    {
        $show = Order::find($id);
        if (!$show) {
            return response()->json(["message" => ""], 404);

        }
        return response()->json($show, 200);
    }


    public function edit(Order $orders)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $update = Order::find($id);
        if (!$update) {
            return response()->json(["message" => "tambahkan barang anda"], 404);
        }
        $update->update($request->all());
        return response()->json($update, 200);
    }


    public function destroy($id)
    {
        $delete = Order::find($id);
        if (!$delete) {
            return response()->json(["message" => "Not Found"], 404);
        }
        $delete->delete();
        return response()->json($delete, 200);
    }
}
