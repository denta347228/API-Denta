<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $index = Product::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Orders retrieved successfully',
            'data' => $index
        ], 200);
    }

    public function create()
    {

    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required|string|max:255",
            'category_id' => "required|integer",
            'price' => "required|integer"
        ]);

        $categorie = Categories::find($request->category_id);

        if (empty($categorie)) {
            return response()->json(["message" => __("file kosong")], 404);
        }

        $product = Product::create($request->all());

        // Membuat notifikasi sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => $product
        ], 200);

    }




    public function show($id)
    {
        $show = Product::find($id);
        if (!$show) {
            return response()->json(["message" => ""], 404);

        }
        return response()->json($show, 200);
    }


    public function edit(Product $orders)
    {
    
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required|string|max:255",
            'category_id' => "required|integer",
            'price' => "required|integer"
        ]);
        $update = Product::find($id);
        if (!$update) {
            return response()->json(["message" => "tambahkan barang anda"], 404);
        }
        $update->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => $update
        ], 200);
    }


    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            if (!$product) {
                return response()->json(["message" => "Product not found"], 404);
            }
            if (Order::where('product_id', $id)->exists()) {
                return response()->json(["message" => "Product terhubung di Order"], 400);
            }
            $product->delete();
            $response = [
                'status' => 'success',
                'message' => 'Product deleted successfully',
            ];
        
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            return response()->json(["message" => $th->getMessage()], 400);
        }
        
    }
}
