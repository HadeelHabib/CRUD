<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;

class ProductController extends Controller
{

    public function index()
    { 
        $product = Product::all();
        return response()->json([
            'status' => 'succses',
            'product' => $product
        ]);
    }


    public function store(Request $request)
    {


        $request->validate(
            [
                'name' => 'required|string',
                'price' => 'required|numeric',
            ]
        );


        try {
            DB::beginTransaction();
            $product = Product::create([
                'name' => $request->name,
                'price' => $request->price

            ]);
            DB::commit();
            return response()->json([
                'status' => 'succses',
                'product' => $product
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
            return response()->json([
                'status' => 'error',

            ]);
        }
    }


    public function show(Product $product)
    {
        return response()->json([
            'status' => 'succses',
            'product' => $product
        ]);
    }


    public function update(Request $request, Product $product)
    {
        $request->validate(
            [
                'name'=>'nullable|string',
                'price'=>'nullable|numeric',
            ]);
        $Data = [];
        try {
            DB::beginTransaction();

            if (isset($request->name)) {
                $Data['name'] = $request->name;
            }
            if (isset($request->price)) {
                $Data['price'] = $request->price;
            }



            DB::commit();
            $product->update($Data);
            return response()->json([
                'status' => "success",
                "message" => "product updated successfully"
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            log::error($th);
            return response()->json([
                'status' => "Failed"
            ], 500);
        }
    }

    public function destroy(Product $product)
    {

        $product->delete();
        return response()->json([
            'status' => 'succses',
            'product' => $product
        ]);
    }
}
