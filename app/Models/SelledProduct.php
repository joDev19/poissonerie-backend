<?php

namespace App\Models;

use App\Events\SelledProductCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class SelledProduct extends Model
{
    //
    protected $fillable = [
        'vente_id',
        'product_id',
        'quantity',
        'type',
        'quantity_per_box',
        'sell_price',
    ];
    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected $dispatchesEvents = [
        'created' => SelledProductCreated::class,
    ];
}
