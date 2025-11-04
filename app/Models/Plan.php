<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'monthly_price',
        'annual_price',
        'annual_discount_percentage',
        'description',
    ];

    protected $casts = [
        'monthly_price' => 'float',
        'annual_price' => 'float',
        'annual_discount_percentage' => 'integer',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
