<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Sale_detail;
use Illuminate\Http\Request;
use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = auth()->user();
        $product = new Product();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:product|min:4',
            'description' => 'string|between:10,200',
            'price' => 'numeric|required',
            'stock' => 'integer|required',
            'id_category' => 'integer|required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Category::find($request->input('id_category'));
        if (!isset($category)) {
            return response()->json(['error' => 'Category does not exist'], 404);
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->id_category = $request->input('id_category');
        $product->id_user = $user->id;
        $response = $product->save();

        return response()->json(['response' => $response], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::find($id);
        if (!isset($product)) {
            return response()->json(['error' => 'Product does not exist'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $user = auth()->user();
        $product = Product::find($id);

        if (!isset($product)) {
            return response()->json(['error' => 'Product does not exist'], 401);
        }

        if ($user->id !== $product->id_user) {
            return response()->json(['error' => 'User unauthorized'], 400);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|unique:product|min:4',
            'description' => 'string|between:10,200',
            'price' => 'numeric',
            'stock' => 'integer',
            'id_category' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Category::find($request->input('id_category') || $product->id_category);
        if (!isset($category)) {
            return response()->json(['error' => 'Category does not exist'], 404);
        }

        if (!empty($request->input('name'))) $product->name = $request->input('name');
        if (!empty($request->input('description'))) $product->description = $request->input('description');
        if (!empty($request->input('price'))) $product->price = $request->input('price');
        if (!empty($request->input('stock'))) $product->stock = $request->input('stock');
        if (!empty($request->input('id_category'))) $product->id_category = $request->input('id_category');
        $response = $product->update();

        return response()->json(['response' => $response], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTotalSaleProduct($id)
    {

        $user = auth()->user();
        $product = Product::find($id);
        $details = Sale_detail::where('id_product', $product->id)->get();

        if (!isset($product)) {
            return response()->json(['error' => 'Product does not exist'], 404);
        }

        if ($user->id !== $product->id_user) {
            return response()->json(['error' => 'User unauthorized'], 400);
        }

        if (!isset($details)) {
            return response()->json(['error' => 'Product without sales'], 400);
        }

        $sold = 0;
        $cancel = 0;
        $profit = 0;

        foreach ($details as $detail) {

            if (!empty($detail->cancel_detail)) {
                $cancel += 1;
            } else {
                $sold  += 1;
                $profit += $detail->total_price;
            }
        }


        return response()->json([
            'Sold' => $sold,
            'Cancel' => $cancel,
            'Profit' => $profit,
            'response' => $detail
        ], 201);
    }

    public function getTotalSale()
    {

        $user = auth()->user();
        $products = Product::where('id_user', $user->id)->get();

        if (!isset($products)) {
            return response()->json(['error' => 'No Products'], 404);
        }

        $sold = 0;
        $cancel = 0;
        $profit = 0;

        foreach ($products as $product) {

            $details = Sale_detail::where('id_product', $product->id)->get();
            foreach ($details as $detail) {

                if (!empty($detail->cancel_detail)) {
                    $cancel += 1;
                } else {
                    $sold  += 1;
                    $profit += $detail->total_price;
                }
            }
        }


        return response()->json([
            'Sold' => $sold,
            'Cancel' => $cancel,
            'Profit' => $profit,
        ], 201);
    }
}
