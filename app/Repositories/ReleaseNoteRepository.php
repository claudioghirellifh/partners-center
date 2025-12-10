<?php

namespace App\Repositories;

use App\Models\ReleaseNote;
use Illuminate\Support\Collection;

class ReleaseNoteRepository
{
    public function current(): ?ReleaseNote
    {
        return ReleaseNote::query()
            ->visible()
            ->orderByDesc('is_current')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->first();
    }

    public function latest(int $limit = 5): Collection
    {
        return ReleaseNote::query()
            ->visible()
            ->orderByDesc('is_current')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    public function setCurrent(ReleaseNote $releaseNote): void
    {
        ReleaseNote::query()
            ->whereKeyNot($releaseNote->id)
            ->update(['is_current' => false]);

        $releaseNote->is_current = true;
        $releaseNote->is_visible = true;

        if ($releaseNote->published_at === null) {
            $releaseNote->published_at = now();
        }

        $releaseNote->save();
    }
}
