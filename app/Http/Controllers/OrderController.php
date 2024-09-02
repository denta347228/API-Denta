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
        $quan = $request->quantity;
        $totalprice = $price * $quan;

        $order = order::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalprice,
            'order_date' => now()

        ]);
        return response()->json($order, 200);
    }

    public function report()
    {
        // Mengambil data dari join antara orders, users, products, dan categories
        $orders = Order::leftJoin('users as u', 'u.id', '=', 'orders.user_id')
            ->leftJoin('products as p', 'p.id', '=', 'orders.product_id')
            ->leftJoin('categories as c', 'c.id', '=', 'p.category_id')
            ->select(
                'orders.id',
                'p.name as product_name',
                'c.name as category_name',
                'orders.quantity',
                'orders.total_price',
                'u.name as customer_name',
                'orders.order_date'
            )
            ->get();

        // Menghitung total orders dan total revenue
        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_price');

        // Menyiapkan struktur response
        $response = [
            'status' => 'success',
            'message' => 'Order report generated successfully',
            'data' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'orders' => $orders->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'product_name' => $order->product_name,
                        'category_name' => $order->category_name,
                        'quantity' => $order->quantity,
                        'total_price' => $order->total_price,
                        'customer_name' => $order->customer_name,
                        'order_date' => \Carbon\Carbon::parse($order->order_date)->toIso8601String(),
                    ];
                })
            ]
        ];

        // Mengembalikan response dalam bentuk JSON
        return response()->json($response);
    }


    public function edit(Order $orders)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => "required|integer",
            'quantity' => "required|integer"
        ]);
        $product = Product::find($request->product_id);
        if (empty($product)) {
            return response()->json(["message" => __("file kosong")], 404);

        }
        $price = $product->price;
        $quan = $request->quantity;
        $totalprice = $price * $quan;
        $update = Order::find($id);

        if (!$update) {
            return response()->json(["message" => "tambahkan barang anda"], 404);
        }
        $update->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalprice,
        ]);
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
