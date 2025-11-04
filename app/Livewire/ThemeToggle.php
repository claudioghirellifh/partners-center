<?php

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ThemeToggle extends Component
{
    public string $theme = 'light';

    public function mount(): void
    {
        $this->theme = session('theme', 'light');
    }

    public function toggle(): void
    {
        $this->theme = $this->theme === 'dark' ? 'light' : 'dark';

        session()->put('theme', $this->theme);

        $this->dispatch('theme-updated', theme: $this->theme);
    }

    public function render(): View
    {
        return view('livewire.theme-toggle');
    }
}
