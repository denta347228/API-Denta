<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $index = User::all();
        return response()->json($index, 200);
    }


    public function create()
    {

    }


    public function store(Request $request)
    {
        $temp= User::create($request->all());
        return response()->json($temp, 200);
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
        $update = User::find($id);
        if (!$update) {
            return response()->json(["message" => "tambahkan barang anda"], 404);
        }
        $update->update($request->all());
        return response()->json($update, 200);
    }


    public function destroy($id)
    {
        $delete = User::find($id);
        if (!$delete) {
            return response()->json(["message" => "Not Found"], 404);
        }
        $delete->delete();
        return response()->json($delete, 200);
    }
}
