<?php

namespace App\Http\Livewire;

use Livewire\Component;

class LocaleSelector extends Component
{
    public string $locale = 'en';

    public function toggleLocale()
    {
        $this->locale = app()->getLocale() === 'en' ? 'pt_BR' : 'en';

        session()->put('app_locale', $this->locale);

        return redirect()->to(url()->previous());
    }

    public function mount()
    {
        $this->locale = app()->getLocale();
    }

    public function render()
    {
        return view('livewire.locale-selector');
    }
}
