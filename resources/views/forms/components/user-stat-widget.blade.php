<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ($this->widgets as $widget)
                <div
                    class="col-span-1 relative overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 shadow sm:px-6 sm:pt-6">
                    <dt>
                        <div class="absolute rounded-md bg-blue-500 p-3">
                            @svg($widget['icon'], 'h-6 w-6 text-white')
                        </div>
                        <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-300">
                            {{ $widget['label'] }}</p>
                    </dt>
                    <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            @if ($widget['value_type'] == 'integer')
                                {{ $widget['value'] }}
                            @else
                                {{ sprintf('%02d:%02d', floor($widget['value'] / 60), $widget['value'] % 60) }}
                            @endif
                        </p>
                    </dd>
                </div>
            @endforeach

        </div>

    </div>
</x-dynamic-component>
