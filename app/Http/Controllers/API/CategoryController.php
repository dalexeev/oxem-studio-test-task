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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:200',
            'parent_id' => 'required|nullable|integer|min:0',
            'external_id' => 'required|string|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json_fail('Invalid arguments');
        }

        $category = new Category;

        $category->name = $request->name;
        $category->parent_id = $request->parent_id; // TODO: Предотвращение циклических ссылок.
        $category->external_id = $request->external_id;

        $category->save();

        return response()->json_success(['id' => $category->id]);
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
     * Редактирует указанную категорию.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //////////////////////// Частично дублирует ////////////////
    }

    /**
     * Полностью удаляет указанную категорию.
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
