<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Хелпер для методов store() и update().
     *
     * @param  App\Product  $product
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $with_id
     * @return \Illuminate\Http\Response
     */
    private function _update($product, $request, $with_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'description' => 'required|string|max:1000',
            'price' => 'required|numeric|min:0',
            'balance' => 'required|integer|min:0',
            'external_id' => 'required|string|max:200',
            'categories' => ['required', 'regex:/^(null|\d+(,\d+)*)$/'],
        ]);

        if ($validator->fails()) {
            return response()->json_fail('Invalid arguments');
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->balance = $request->balance;
        $product->external_id = $request->external_id;

        $product->save();

        return response()->json_success($with_id ? ['id' => $product->id] : []);
    }

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
        $product = new Product();

        return self::_update($product, $request, true);
    }

    /**
     * Возвращает указанный товар.
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
     * Обновляет указанный товар.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json_fail('Product not found');
        }

        return self::_update($product, $request, false);
    }

    /**
     * Навсегда удаляет указанный товар.
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
