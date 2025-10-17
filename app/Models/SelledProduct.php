<?php

namespace App\Models;

use App\Events\SelledProductCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function surplusSale() {
        return $this->hasMany(SurplusVente::class, 'selled_product_id')->where('vente_id', $this->vente_id)->first();
    }

    protected $dispatchesEvents = [
        'created' => SelledProductCreated::class,
    ];
}
