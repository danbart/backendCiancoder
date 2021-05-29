<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Sale_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Validator;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();
        $sales = Sale::where('id_user', $user->id)->get();
        return response()->json($sales, 200);
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

        $sale = Sale::where('id_user', $user->id)->whereNull('end_sale')->first();

        if (isset($sale)) {
            return response()->json([
                $sale
            ], 201);
        }


        $sale = Sale::create(array_merge(
            ['id_user' => $user->id]
        ));

        return response()->json([
            $sale
        ], 201);
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
        $user = auth()->user();
        $sale = Sale::find($id);

        if (!isset($sale)) {
            return response()->json(['error' => 'Sale does not exist'], 404);
        }

        if ($user->id !== $sale->id_user) {
            return response()->json(['error' => 'Sale does not exist'], 404);
        }

        $sales = Sale::where('id_user', [$user->id])->where('id', [$id])->get();
        return response()->json($sales, 200);
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

    /**
     * Display details sale to specific sale
     */
    public function getSaleDetail($id)
    {

        $user = auth()->user();
        $sale = Sale::find($id);

        if (!isset($sale)) {
            return response()->json(['error' => 'No Exist'], 404);
        }

        if ($user->id !== $sale->id_user) {
            return response()->json(['error' => 'User unauthorized'], 404);
        }

        $saleDetail = Sale_detail::where('id_sale', [$sale->id])->get();
        return response()->json($saleDetail, 200);
    }

    public function postSaleDetail(Request $request, $id)
    {

        $user = auth()->user();
        $sale = Sale::find($id);

        if (!isset($sale)) {
            return response()->json(['error' => 'Sale does not exist'], 404);
        }

        if (!empty($sale->end_sale)) {
            return response()->json(['error' => 'Completed sale'], 404);
        }

        if ($user->id !== $sale->id_user) {
            return response()->json(['error' => 'User unauthorized'], 404);
        }

        $saleDetail = new Sale_detail();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|integer',
            'id_product' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $product = Product::find($request->input('id_product'));

        if (!isset($product)) {
            return response()->json(['error' => 'Product does not exist'], 404);
        }

        if ($product->id_user === $user->id) {
            return response()->json(['error' => 'cannot buy your own product'], 400);
        }

        if ($product->stock <= 0 || $product->stock < $request->input('amount')) {
            return response()->json(['error' => 'Product out of stock'], 400);
        }

        $saleDetail->amount = $request->input('amount');
        $saleDetail->unit_price = $product->price;
        $saleDetail->total_price = $product->price * $request->input('amount');
        $saleDetail->id_product = $product->id;
        $saleDetail->id_sale = $sale->id;
        $response = $saleDetail->save();

        $product->stock = $product->stock - $request->input('amount');
        $product->update();

        return response()->json(['response' => $response], 201);
    }

    public function putEndSale($id)
    {
        $user = auth()->user();
        $sale = Sale::find($id);

        if (!isset($sale)) {
            return response()->json(['error' => 'Sale does not exist'], 404);
        }

        if (!empty($sale->end_sale)) {
            return response()->json(['error' => 'Completed sale'], 400);
        }

        if ($user->id !== $sale->id_user) {
            return response()->json(['error' => 'User unauthorized'], 404);
        }

        $sale->end_sale = date('Y-m-d H:i:s');
        $response = $sale->update();
        return response()->json(['response' => $response], 201);
    }

    public function putCancelDetail($idSale, $idDetail)
    {
        $user = auth()->user();
        $sale = Sale::find($idSale);
        $detail = Sale_detail::find($idDetail);
        $product = Product::find($detail->id_product);

        if (!isset($sale)) {
            return response()->json(['error' => 'Sale does not exist'], 404);
        }

        if (!empty($sale->end_sale)) {
            return response()->json(['error' => 'Completed sale'], 400);
        }

        if ($user->id !== $sale->id_user) {
            return response()->json(['error' => 'User unauthorized'], 400);
        }

        if (!isset($detail)) {
            return response()->json(['error' => 'Sale does not exist'], 404);
        }

        $product->stock = $product->stock + $detail->amount;
        $product->update();

        $detail->cancel_detail = date('Y-m-d H:i:s');
        $response = $detail->update();

        return response()->json(['response' => $response], 201);
    }
}
