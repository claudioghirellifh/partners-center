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
        'billing_origin',
        'iugu_subscription_id',
        'charge_setup',
        'setup_fee',
        'notes',
    ];

    protected $casts = [
        'charge_setup' => 'boolean',
        'setup_fee' => 'float',
    ];

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
