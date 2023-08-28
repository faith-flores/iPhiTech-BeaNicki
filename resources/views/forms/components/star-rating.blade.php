<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}'), rating: 0, hoverRating: -1 }" x-init="rating = state">
        <div class="flex align-middle">
            @for ($i = 1; $i <= 5; $i++)
                <div
                    @mouseover="hoverRating = {{ $i }}; rating = -1;"
                    @mouseout="hoverRating = -1; rating = state"
                    @click="state = {{ $i }}"
                    @class(['cursor-pointer'])
                >
                    <svg x-bind:class="(hoverRating !== -1 && {{ $i }} <= hoverRating) || ({{ $i }} <= rating) ? 'text-yellow-300' : 'text-neutral-400'" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                </div>
            @endfor

            <!-- X-mark SVG to reset rating -->
            <template x-if="state > 0">
                <div @click="state = 0; rating = 0;" class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-300 ml-3 cursor-pointer">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                      </svg>
                </div>
            </template>
        </div>
    </div>
</x-dynamic-component>
