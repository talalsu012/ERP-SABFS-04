<x-filament-panels::page>
    <x-filament-panels::form wire:submit="applyFilters">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedHeaderActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ $this->table }}
</x-filament-panels::page>
