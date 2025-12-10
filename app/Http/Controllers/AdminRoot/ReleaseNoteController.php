<?php

namespace App\Http\Controllers\AdminRoot;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRoot\ReleaseNoteRequest;
use App\Models\ReleaseNote;
use App\Repositories\ReleaseNoteRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReleaseNoteController extends Controller
{
    public function __construct(private readonly ReleaseNoteRepository $releaseNotes)
    {
    }

    public function index(): View
    {
        $releaseNotes = ReleaseNote::query()
            ->orderByDesc('is_current')
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(15);

        return view('adminroot.release-notes.index', compact('releaseNotes'));
    }

    public function create(): View
    {
        return view('adminroot.release-notes.create', [
            'releaseNote' => new ReleaseNote(),
        ]);
    }

    public function store(ReleaseNoteRequest $request): RedirectResponse
    {
        $data = $this->preparePayload($request->validated());

        DB::transaction(function () use ($data): void {
            $releaseNote = ReleaseNote::create($data);

            if ($releaseNote->is_current) {
                $this->releaseNotes->setCurrent($releaseNote);
            }
        });

        return redirect()
            ->route('adminroot.release-notes.index')
            ->with('status', 'Nota de versão criada com sucesso.');
    }

    public function edit(ReleaseNote $releaseNote): View
    {
        return view('adminroot.release-notes.edit', compact('releaseNote'));
    }

    public function update(ReleaseNoteRequest $request, ReleaseNote $releaseNote): RedirectResponse
    {
        $data = $this->preparePayload($request->validated());

        DB::transaction(function () use ($releaseNote, $data): void {
            $releaseNote->update($data);

            if ($releaseNote->is_current) {
                $this->releaseNotes->setCurrent($releaseNote);
            }
        });

        return redirect()
            ->route('adminroot.release-notes.index')
            ->with('status', 'Nota de versão atualizada com sucesso.');
    }

    public function destroy(ReleaseNote $releaseNote): RedirectResponse
    {
        $releaseNote->delete();

        return redirect()
            ->route('adminroot.release-notes.index')
            ->with('status', 'Nota de versão removida.');
    }

    private function preparePayload(array $data): array
    {
        $data['is_current'] = (bool) ($data['is_current'] ?? false);
        $data['is_visible'] = (bool) ($data['is_visible'] ?? false);

        if ($data['is_visible'] && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (! $data['is_visible']) {
            $data['published_at'] = null;
        }

        if (empty($data['alert_message'])) {
            $data['alert_message'] = null;
            $data['alert_level'] = null;
        } elseif (empty($data['alert_level'])) {
            $data['alert_level'] = ReleaseNote::ALERT_LEVEL_INFO;
        }

        return $data;
    }
}
