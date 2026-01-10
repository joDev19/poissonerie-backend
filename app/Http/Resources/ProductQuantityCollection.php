<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductQuantityCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'data' => $this->collection->transform(function($item){
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'kilo_once_quantity' => $item->kilo_once_quantity,
                    'price' => $item->price,
                    'stock_box' => $item->box,
                    'stock_kilo' => $item->kg,
                ];
            })
        ];
    }
}
