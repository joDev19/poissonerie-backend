<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VenteRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'date' => $this->date,
            'selled_products' => SelledProductResource::collection($this->whenLoaded('selledProducts')),
            'selled_products_name' => $this->whenLoaded('selledProducts', function () {
                return $this->selledProducts->pluck('product.name');
            }),
            'total_price'=> $this->total_price,
            'invoice' => $this->invoice,
            'buyer_infos' => $this->buyer_infos,
            'created_at' => $this->created_at,
        ];
    }
}
