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
        'status',
        'customer_id',
        'name',
        'client_email',
        'billing_origin',
        'iugu_subscription_id',
        'charge_setup',
        'setup_fee',
        'notes',
        'store_domain',
        'use_temp_domain',
        'store_name',
        'store_admin_name',
        'store_admin_email',
        'store_admin_password',
    ];

    protected $casts = [
        'charge_setup' => 'boolean',
        'setup_fee' => 'float',
        'use_temp_domain' => 'boolean',
    ];

    protected $hidden = [
        'store_admin_password',
    ];

    public const ORIGIN_MANUAL = 'manual';
    public const ORIGIN_IUGU = 'iugu';

    public const STATUS_REQUESTED = 'installation_requested';
    public const STATUS_INSTALLING = 'installing';
    public const STATUS_CANCELLED = 'installation_cancelled';
    public const STATUS_DONE = 'installation_done';

    public static function statusBadge(string $status): array
    {
        $map = [
            self::STATUS_REQUESTED => ['label' => 'Solicitação de Instalação', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-100'],
            self::STATUS_INSTALLING => ['label' => 'Em Instalação', 'class' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-100'],
            self::STATUS_CANCELLED => ['label' => 'Instalação Cancelada', 'class' => 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-100'],
            self::STATUS_DONE => ['label' => 'Instalação Concluída', 'class' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-100'],
        ];

        return $map[$status] ?? $map[self::STATUS_REQUESTED];
    }

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
