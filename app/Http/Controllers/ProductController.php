<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $index = Product::all();
        return response()->json($index, 200);
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
        return response()->json($product, 200);

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
        //
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
        return response()->json($update, 200);
    }


    public function destroy($id)
    {
        $delete = Product::find($id);
        if (!$delete) {
            return response()->json(["message" => "Not Found"], 404);
        }
        $delete->delete();
        return response()->json($delete, 200);
    }
}
