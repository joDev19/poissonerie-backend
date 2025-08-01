<?php

namespace App\Models;

use App\Events\ProductCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    protected $fillable = ['name', 'marque_id', 'price_kilo_min', 'price_kilo_max', 'price_carton_min', 'price_carton_max', 'price_unit_min', 'price_unit_max', 'category', 'user_id'];
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
