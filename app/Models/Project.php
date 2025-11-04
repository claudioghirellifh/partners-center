<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'plan_id',
        'name',
        'billing_cycle',
        'starts_on',
        'notes',
    ];

    protected $casts = [
        'starts_on' => 'date',
    ];

    public const BILLING_MONTHLY = 'monthly';
    public const BILLING_ANNUAL = 'annual';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
