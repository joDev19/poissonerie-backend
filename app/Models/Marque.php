<?php

namespace App\Models;
use App\Policies\MarquePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
#[UsePolicy(MarquePolicy::class)]
class Marque extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'user_id'];
    public function products(): HasMany{
        return $this->hasMany(Product::class);
    }
}
