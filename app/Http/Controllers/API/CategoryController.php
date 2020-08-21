<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Category;
use App\Product;
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
            'parent_id' => 'nullable|integer|min:0',
            'external_id' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json_fail('Invalid arguments', [$validator->errors()]);
        }

        $category->name = $request->name;
        $category->parent_id = $request->parent_id ?? 'NULL';
        $category->external_id = $request->external_id;

        // TODO: Предотвращение циклических ссылок.

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
        return response()->json_success(['categories' => Category::all()]);
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
     * Возвращает список товаров в категории.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        ////////////////// см. Product. ///////////////////
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
        $category = Product::find($id);

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
