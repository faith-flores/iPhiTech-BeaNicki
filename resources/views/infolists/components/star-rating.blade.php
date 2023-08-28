@php
    use Filament\Support\Enums\ActionSize;
    use Filament\Support\Enums\IconSize;
@endphp

@props([
    'badge' => null,
    'badgeColor' => 'primary',
    'color' => 'primary',
    'disabled' => false,
    'form' => null,
    'icon' => null,
    'iconAlias' => null,
    'iconSize' => null,
    'keyBindings' => null,
    'label' => null,
    'size' => 'md',
    'tag' => 'button',
    'tooltip' => null,
    'type' => 'button',
])
<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        {{ $getState() }}
        @php
            $rating = $getState();

            $iconClasses = \Illuminate\Support\Arr::toCssClasses([
            'fi-icon-btn-icon',
            match ($iconSize) {
                IconSize::Small, 'sm' => 'h-4 w-4',
                IconSize::Medium, 'md' => 'h-5 w-5',
                IconSize::Large, 'lg' => 'h-6 w-6',
                default => $iconSize,
            },
        ]);
        @endphp
        @for ($i = 1; $i <= 5; $i++)
            <x-filament::icon
                :alias="$iconAlias"
                :icon="$icon"
                :class="$iconClasses"
                :icon="$iconStyle"
            />
        @endfor
    </div>
</x-dynamic-component>
