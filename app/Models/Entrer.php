<?php

namespace App\Models;

use App\Events\EntrerCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entrer extends Model
{
    //
    protected $fillable = ['date', 'product_id', 'price', 'box_quantity', 'kilo_quantity', 'fournisseur_id'];
    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }
    public function fournisseur(): BelongsTo{
        return $this->belongsTo(related: Fournisseur::class);
    }
    protected $dispatchesEvents = [
        'created' => EntrerCreated::class,
    ];

}
