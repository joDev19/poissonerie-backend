<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SelledProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'type_achat' => $this->type,
            'product' => $this->whenLoaded('product', function () {
                return $this->product;
            }),
            'total_price' => $this->quantity * $this->sell_price, // Assuming sell_price is the price per unit
            'quantity_per_box' => $this->quantity_per_box,
        ];
    }
}
