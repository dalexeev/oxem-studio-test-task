<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\ProductCollection;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Хелпер для методов store() и update().
     *
     * @param  App\Category  $category
     * @param  \Illuminate\Http\Request  $request
     * @param  bool  $with_id
     * @return \Illuminate\Http\Response
     */
    private function _update($category, $request, $with_id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'parent_id' => ['required', 'regex:/^(null|\d+)$/'],
            'external_id' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json_fail('Invalid arguments');
        }

        $category->name = $request->name;
        $category->parent_id = ($request->parent_id == 'null') ? null : $request->parent_id;
        $category->external_id = $request->external_id;

        // TODO: Предотвращение циклических ссылок через parent_id.
        // Родительская категория может отсутствовать!

        $category->save();

        return response()->json_success($with_id ? ['id' => $category->id] : []);
    }

    /**
     * Возвращает список всех категорий.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json_success(['categories' => new CategoryCollection(Category::all())]);
    }

    /**
     * Добавляет новую категорию и возвращает её id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category();

        return self::_update($category, $request, true);
    }

    /**
     * Возвращает список всех товаров в категории.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json_fail('Category not found');
        }

        $products = $category->products;

        return response()->json_success([
            'category' => new CategoryResource($category),
            'products' => new ProductCollection($products),
        ]);
    }

    /**
     * Обновляет указанную категорию.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json_fail('Category not found');
        }

        return self::_update($category, $request, false);
    }

    /**
     * Навсегда удаляет указанную категорию.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json_fail('Category not found');
        }

        $category->delete();

        return response()->json_success();
    }
}
