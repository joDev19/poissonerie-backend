<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = ['name', 'marque_id'];
    // protected $hidden = ['marque_id'];
    public function marque(): BelongsTo{
        return $this->belongsTo(Marque::class);
    }
}
