<button type="button" wire:click="toggleLocale" class="cursor-pointer" title="{{ __('Toggle Locale') }}">
    <img 
        src="{{ asset('img/countries/' . $locale . '-flag.svg') }}"
        alt="{{ __('Flag') }}" 
        class="h-6 w-6"
    >
</button>