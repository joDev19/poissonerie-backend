<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurplusVente extends Model
{
    protected $fillable = [
        "vente_id",
        "selled_product_id",
        "excess_quantity",
    ];
}
