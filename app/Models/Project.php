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
        'customer_id',
        'name',
        'client_email',
        'billing_cycle',
        'billing_origin',
        'iugu_subscription_id',
        'charge_setup',
        'setup_fee',
        'starts_on',
        'notes',
    ];

    protected $casts = [
        'starts_on' => 'date',
        'charge_setup' => 'boolean',
        'setup_fee' => 'float',
    ];

    public const BILLING_MONTHLY = 'monthly';
    public const BILLING_ANNUAL = 'annual';
    public const ORIGIN_MANUAL = 'manual';
    public const ORIGIN_IUGU = 'iugu';

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
