<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class);
    }

    public function procurements(): HasMany
    {
        return $this->hasMany(Procurement::class);
    }
}
