<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fournisseur extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'contact', 'adresse', 'user_id'];
    //
    public function approvisionements(): HasMany{
        return $this->hasMany(Entrer::class);
    }
}
