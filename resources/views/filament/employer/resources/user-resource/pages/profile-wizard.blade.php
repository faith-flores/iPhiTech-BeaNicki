<x-filament-panels::page>
    <div class="py-10 h-full">
        <form wire:submit.prevent="submit">
            {{ $this->form }}
        </form>
    </div>
</x-filament-panels::page>

