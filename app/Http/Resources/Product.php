<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Преобразует ресурс товара в массив.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $categories[] = $category->id;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'price' => $this->price,
            'balance' => $this->balance,
            'external_id' => $this->external_id,
            'categories' => $categories,
        ];
    }
}
