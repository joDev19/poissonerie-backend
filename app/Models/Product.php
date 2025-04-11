<?php

namespace App\Models;

use App\Events\ProductCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = ['name', 'marque_id', 'price_kilo', 'price_carton'];
    // protected $hidden = ['marque_id'];
    public function marque(): BelongsTo{
        return $this->belongsTo(Marque::class);
    }
    public function quantity(): HasOne{
        return $this->hasOne(ProductQuantity::class, 'product_id');
    }
    protected $dispatchesEvents = [
        'created' => ProductCreated::class,
    ];
    protected $with = ['quantity'];
}
