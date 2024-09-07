<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $index = User::all();
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
        $user = User::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => $user
        ], 200);


    }


    public function show($id)
    {
        $show = User::find($id);
        if (!$show) {
            return response()->json(["message" => ""], 404);

        }
        return response()->json($show, 200);
    }


    public function edit(User $orders)
    {
        //
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => "required|string|max:255",
            'customer_address' => "string|max:255",

        ]);

        $update = User::find($id);
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
        $delete = User::find($id);
        if (!$delete) {
            return response()->json(["message" => "Not Found"], 404);
        }
        $delete->delete();
        $response = [
            'status' => 'success',
            'message' => 'Order deleted successfully',
        ];
        return response()->json($response, 200);
    }
}
