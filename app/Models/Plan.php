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
        'description',
        'plan_id',
    ];

    protected $casts = [
        'monthly_price' => 'float',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
