<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vente extends Model
{
    protected $fillable = [
        'buyer_infos',
        "date",
        "invoice",
        'is_paid',
        "price",
        "amount_paid",
        "contains_gros",
        "type",
        "user_id",
    ];


    public function selledProducts(): HasMany{
        return $this->hasMany(SelledProduct::class);
    }


    // protected $with = [
    //     'selledProducts',
    // ];
    protected $casts = [
        'buyer_infos' => 'array',
        'date' => 'datetime',
        'is_paid' => 'boolean',
    ];

}
