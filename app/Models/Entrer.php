<?php

namespace App\Models;

use App\Events\EntrerCreated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Entrer extends Model
{
    use Notifiable;
    protected $dispatchesEvents = [
        'created' => EntrerCreated::class,
    ];
    //
    protected $fillable = ['date', 'product_id', 'price', 'box_quantity', 'kilo_once_quantity', 'fournisseur_id', 'unit_quantity', 'user_id',];
    public function product(): BelongsTo{
        return $this->belongsTo(Product::class);
    }
    public function fournisseur(): BelongsTo{
        return $this->belongsTo(related: Fournisseur::class);
    }
    public function getKiloQuantityAttribute(): float
    {
        return $this->box_quantity * $this->kilo_once_quantity;
    }
    protected $appends = ['kilo_quantity'];
}
