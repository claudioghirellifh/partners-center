<?php

namespace App\Repositories;

use App\Models\Setting;

class SettingRepository
{
    public function get(string $group, string $key, $default = null): mixed
    {
        return Setting::query()
            ->where('group', $group)
            ->where('key', $key)
            ->value('value') ?? $default;
    }

    public function set(string $group, string $key, $value): void
    {
        Setting::query()->updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['value' => $value]
        );
    }
}
