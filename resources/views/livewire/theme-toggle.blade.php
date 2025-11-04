<div>
    <button
        type="button"
        wire:click="toggle"
        wire:loading.attr="disabled"
        aria-pressed="{{ $theme === 'dark' ? 'true' : 'false' }}"
        class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-[#F27327] dark:border-slate-600 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800"
    >
        <span class="flex items-center gap-2" wire:loading.remove>
            @if ($theme === 'dark')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 18a1 1 0 0 1 1 1v2a1 1 0 1 1-2 0v-2a1 1 0 0 1 1-1Zm6.22-2.78a1 1 0 0 1 1.42 1.42l-1.42 1.42a1 1 0 0 1-1.42-1.42ZM5.76 5.76a1 1 0 0 1 1.42 0L8.6 7.17a1 1 0 1 1-1.42 1.42L5.76 7.18a1 1 0 0 1 0-1.42ZM12 6a1 1 0 0 1-1-1V3a1 1 0 1 1 2 0v2a1 1 0 0 1-1 1Zm-7 5a1 1 0 0 1 1 1 1 1 0 0 1-1 1H3a1 1 0 0 1 0-2Zm16 0a1 1 0 0 1 0 2h-2a1 1 0 0 1 0-2ZM8.6 16.83a1 1 0 0 1 0 1.41l-1.42 1.42a1 1 0 0 1-1.41-1.41l1.41-1.42a1 1 0 0 1 1.42 0Zm8-8 1.41-1.41a1 1 0 0 1 1.42 1.41l-1.42 1.42a1 1 0 1 1-1.41-1.42Zm-3.19 8.9a3 3 0 1 1-4.24-4.24 3 3 0 0 1 4.24 4.24Z" />
                </svg>
                <span>Modo claro</span>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M21.64 13a1 1 0 0 0-1.05-.14 8 8 0 0 1-10.45-10.45 1 1 0 0 0-1.19-1.31A10 10 0 1 0 22 14.05a1 1 0 0 0-.36-1.05Z" />
                </svg>
                <span>Modo escuro</span>
            @endif
        </span>
        <span class="hidden items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400" wire:loading.flex>
            <svg class="h-4 w-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4l3-3-3-3v4a12 12 0 0 0-12 12h4z"></path>
            </svg>
            Alternando...
        </span>
    </button>

    <script>
        (() => {
            const applyTheme = (theme) => {
                const root = document.documentElement;
                root.classList.toggle('dark', theme === 'dark');
            };

            document.addEventListener('livewire:init', () => {
                if (window.__themeToggleRegistered) {
                    return;
                }

                window.__themeToggleRegistered = true;

                Livewire.on('theme-updated', (payload) => {
                    if (!payload || typeof payload.theme !== 'string') {
                        return;
                    }

                    applyTheme(payload.theme);
                });
            });

            applyTheme(@js($theme));
        })();
    </script>
</div>
