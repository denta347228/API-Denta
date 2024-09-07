<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Product;

class CategoriesController extends Controller
{

    public function index()
    {
        $index = Categories::all();
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
        ]);

        $categorie = Categories::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => $categorie
        ], 200);
    }


    public function show($id)
    {
        $show = Categories::find($id);
        if (!$show) {
            return response()->json(["message" => ""], 404);

        }
        return response()->json($show, 200);
    }


    public function edit(Categories $orders)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required|string|max:255",
        ]);
        $update = Categories::find($id);
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
            
            $category = Categories::find($id);
        
    
            if (!$category) {
                return response()->json(["message" => "Category was not found"], 404);
            }
        
          
            if (!empty(Product::where('category_id', $id)->first())) {
                return response()->json(["message" => "Category terhubung dengan Product"], 400);
            }
        
           
            $category->delete();
        
           
            $response = [
                'status' => 'success',
                'message' => 'Category deleted successfully',
                'data' => $category 
            ];
        
            return response()->json($response, 200);
        
        } catch (\Throwable $th) {
            
            return response()->json(["message" => $th->getMessage()], 400);
        }
    }
}
