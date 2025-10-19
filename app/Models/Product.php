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
    protected $fillable = ['name', 'marque_id', 'price_kilo_min', 'price_kilo_max', 'price_carton_min', 'price_carton_max', 'price_unit_min', 'price_unit_max', 'category', 'user_id'];
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
            return $this->quantitys()
                ->whereNotNull('kilo_once_quantity')
                ->select(
                    DB::raw('sum(box) as total_box, sum(kg) as total_kilo, kilo_once_quantity')
                )
                ->groupBy('kilo_once_quantity')->get();
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
    protected $dispatchesEvents = [
        'created' => ProductCreated::class,
    ];
    // protected $with = ['quantity'];
    protected $appends = ['quantities'];
}
