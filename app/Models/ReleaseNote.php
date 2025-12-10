<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleaseNote extends Model
{
    use HasFactory;

    public const ALERT_LEVEL_INFO = 'info';
    public const ALERT_LEVEL_WARNING = 'warning';
    public const ALERT_LEVEL_CRITICAL = 'critical';

    public const ALERT_LEVELS = [
        self::ALERT_LEVEL_INFO,
        self::ALERT_LEVEL_WARNING,
        self::ALERT_LEVEL_CRITICAL,
    ];

    protected $fillable = [
        'version',
        'title',
        'notes',
        'is_current',
        'is_visible',
        'alert_level',
        'alert_message',
        'published_at',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'is_visible' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function notesAsList(): array
    {
        if (! $this->notes) {
            return [];
        }

        return collect(preg_split('/\r\n|\r|\n/', (string) $this->notes))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }

    public function hasAlert(): bool
    {
        return (bool) $this->alert_message;
    }
}
