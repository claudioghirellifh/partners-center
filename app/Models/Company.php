<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'uri',
        'locale',
        'logo_path',
        'favicon_path',
        'brand_color',
    ];

    public function getRouteKeyName(): string
    {
        return 'uri';
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
