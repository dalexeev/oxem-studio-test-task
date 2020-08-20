<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Возвращает список товаров.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'integer|min:1',
            'order_by' => 'in:price,created_at',
            'order' => 'in:asc,desc',
        ]);

        if ($validator->fails()) {
            return response()->json_fail('Invalid arguments');
        }

        $order_by = $request->order_by ?? 'created_at';
        $order = $request->order ?? 'asc';
        $offset = ($request->page ?? 1) - 1;

        $products = Product::orderBy($order_by, $order)->offset($offset)->limit(50)->get();

        return response()->json_success(['products' => $products]);
    }

    /**
     * Добавляет новый товар и возвращает его id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'balance' => 'required|numeric|min:0',
            'external_id' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json_fail('Invalid arguments');
        }

        $product = new Product;

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->balance = $request->balance;
        $product->external_id = $request->external_id;

        $product->save();

        return response()->json_success(['id' => $product->id]);
    }

    /**
     * Возвращает товар.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json_fail('Product not found');
        }

        return response()->json_success(['product' => $product]);
    }

    /**
     * Редактирует указанный товар.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response()->json(['test!!!']);
    }

    /**
     * Полностью удаляет указанный товар.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json_fail('Product not found');
        }

        $product->delete();

        return response()->json_success();
    }
}
