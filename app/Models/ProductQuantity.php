<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductQuantity extends Model
{
    protected $fillable = [
        'product_id',
        'kg',
        'box',
        'kilo_once_quantity',
        'unit',
        'price'
    ];
    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }
}
