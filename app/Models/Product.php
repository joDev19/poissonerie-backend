<?php

namespace App\Models;

use App\Events\ProductCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Product extends Model
{
    protected $fillable = ['name', 'marque_id', 'price_kilo_min', 'price_kilo_max', 'price_unit_min', 'price_unit_max', 'category', 'user_id'];
    // protected $hidden = ['marque_id'];
    public function marque(): BelongsTo
    {
        return $this->belongsTo(Marque::class);
    }
    public function quantitys(): HasMany|HasOne
    {
        // revoir....
        if ($this->category == 'kilo_ou_carton') {
            return $this->hasMany(ProductQuantity::class, 'product_id');
        } else {
            return $this->hasOne(ProductQuantity::class, 'product_id');
        }
    }
    public function _quantities()
    {
        // revoir....
        if ($this->category == 'kilo_ou_carton') {
             $quanties = $this->quantitys()
                ->whereNotNull('kilo_once_quantity')
                ->select(
                    DB::raw('sum(box) as total_box,  sum(kg) as total_kilo, kilo_once_quantity, price')
                )
                ->groupBy('kilo_once_quantity', 'price')->get();
                // $quanties->map(function($items){
                //     $items->price_carton = $this->getCartonPrice($items->kilo_once_quantity);
                //     return $items;
                // });
                return $quanties;
        } else {
            return $this->quantitys()->first()->unit;
        }
        // return $this->hasOne(ProductQuantity::class, 'product_id');
    }
    protected function quantities(): Attribute
    {
        return new Attribute(
            get: fn() => $this->_quantities(),
        );
    }
    public function isKiloOuCarton(): bool
    {
        return $this->category === 'kilo_ou_carton';
    }
    public function isUnite(): bool
    {
        return $this->category === 'unite';
    }



    protected $dispatchesEvents = [
        'created' => ProductCreated::class,
    ];
    // protected $with = ['quantity'];
    protected $appends = ['quantities'];
}
