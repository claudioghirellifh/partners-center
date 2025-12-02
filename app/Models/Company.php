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
        'is_active',
        'locale',
        'logo_path',
        'favicon_path',
        'brand_color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'uri';
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function adminUsers(): HasMany
    {
        return $this->hasMany(User::class)->where('role', User::ROLE_ADMIN);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
