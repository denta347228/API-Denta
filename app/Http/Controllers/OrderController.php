<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        // Ambil semua data orders dan relasi dengan user
        $orders = Order::with('user')->get(); // Menggunakan eager loading

        // Format data sesuai dengan yang diinginkan
        $formattedOrders = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'product_id' => $order->product_id,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'customer_name' => $order->user->name,
                'customer_address' => $order->user->customer_address,
                'created_at' => $order->created_at->format('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => $order->updated_at->format('Y-m-d\TH:i:s.u\Z'),
            ];
        });

        // Buat response JSON dengan status dan pesan sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Orders retrieved successfully',
            'data' => $formattedOrders,
        ], 200);
    }


    public function create()
    {

    }

    public function store(Request $request)
    {
        // $order = Order::create($request->all());
        // return response()->json($order, 200);

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

        $response = [
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => [
                'id' => $order->id,
                'product_id' => $order->product_id,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                'customer_name' => $request->customer_name, // Pastikan ini dikirim dalam request
                'customer_address' => $request->customer_address, // Pastikan ini dikirim dalam request
                'created_at' => $order->created_at->toIso8601String(),
                'updated_at' => $order->updated_at->toIso8601String(),
            ],
        ];
        return response()->json($response, 200);

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
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        $product = Product::find($request->product_id);
        if (empty($product)) {
            return response()->json(["message" => "Product not found"], 404);
        }

        $price = $product->price;
        $quantity = $request->quantity;
        $totalPrice = $price * $quantity;

        $order = Order::find($id);

        if (!$order) {
            return response()->json(["message" => "Order not found"], 404);
        }

        $order->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
        ]);

        $response = [
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => [
                'id' => $order->id,
                'product_id' => $order->product_id,
                'quantity' => $order->quantity,
                'total_price' => $order->total_price,
                // 'customer_name' => $order->customer_name, 
                // 'customer_address' => $order->customer_address, 
                'created_at' => $order->created_at->toIso8601String(),
                'updated_at' => $order->updated_at->toIso8601String(),
            ],
        ];

        return response()->json($response, 200);
    }


    public function destroy($id)
    {
        $order = Order::find($id);

        if (!$order) {
            return response()->json(["message" => "Not Found"], 404);
        }

        $order->delete();

        $response = [
            'status' => 'success',
            'message' => 'Order deleted successfully',
        ];

        return response()->json($response, 200);
    }
}
