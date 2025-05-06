<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    protected $fillable = [
        'buyer_infos',
        "date",
    ];

    protected function totalPrice():Attribute{
        return Attribute::get(function(){
            $this->loadMissing('selledProducts.product');
            return $this->selledProducts->reduce(function ($carry, $selledProduct) {
                $product = $selledProduct->product;
                if(!$product){
                    return $carry;
                }
                if ($product->category === 'unite') {
                    return $carry + ($selledProduct->quantity * $product->prix_unit);
                }
                if ($selledProduct->type === 'gros') {
                    return $carry + ($selledProduct->quantity * $product->price_carton);
                }

                return $carry + ($selledProduct->quantity * $product->price_kilo);
            }, 0);
        });
    }
    public function selledProducts(){
        return $this->hasMany(SelledProduct::class);
    }


    protected $with = [
        'selledProducts',
    ];
    protected $appends = ['total_price',];

}
