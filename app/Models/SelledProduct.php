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
    ];
    public function vente()
    {
        return $this->belongsTo(Vente::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function getTotalPriceAttribute()
    {
        return $this->product->category == 'unite' ? ($this->product->price_unit * $this->quantity) : ($this->type == 'detail' ? ($this->product->price_kilo * $this->quantity) : ($this->product->price_carton * $this->quantity));
    }
    protected $dispatchesEvents = [
        'created' => SelledProductCreated::class,
    ];
    protected $appends = [
        'total_price',
    ];
}
