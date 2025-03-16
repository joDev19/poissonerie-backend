<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    protected $fillable = [
        "product_id",
        "type",
        "quantity",
        "buyer_id",
        "date",
    ];
    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }
    protected function totalPrice():Attribute{
        return new Attribute(
            get: fn () => $this->type == 'detail' ? ($this->product->price_kilo * $this->quantity) : ($this->product->price_carton * $this->quantity)
        );
    }
    protected $appends = ['total_price'];
}
