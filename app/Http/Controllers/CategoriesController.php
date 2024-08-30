<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index()
    {
        $index = Categories::all();
        return response()->json($index, 200);
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
        return response()->json($categorie, 200);
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
        return response()->json($update, 200);
    }


    public function destroy($id)
    {
        $delete = Categories::find($id);
        if (!$delete) {
            return response()->json(["message" => "Not Found"], 404);
        }
        $delete->delete();
        return response()->json($delete, 200);
    }
}
