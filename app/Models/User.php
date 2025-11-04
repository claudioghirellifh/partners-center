<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Notifications\ResetPassword as DefaultResetPassword;
use App\Notifications\AdminResetPassword;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public const ROLE_ROOT = 'root';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_SELLER = 'seller';

    public function isRoot(): bool
    {
        return $this->role === self::ROLE_ROOT;
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Send the password reset notification (customized for Admins/tenants).
     */
    public function sendPasswordResetNotification($token): void
    {
        if ($this->role === self::ROLE_ADMIN && $this->company) {
            $this->notify(new AdminResetPassword($token, $this->company));
            return;
        }

        $this->notify(new DefaultResetPassword($token));
    }
}
