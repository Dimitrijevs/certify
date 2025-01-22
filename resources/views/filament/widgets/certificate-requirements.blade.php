<x-filament-widgets::widget>
    <x-filament::section>
        <style>
            .border-b:last-child {
                border-bottom: none;
            }

            .pb-2:last-child {
                padding-bottom: 0;
            }
        </style>

        @include('components.certificate-requirements-view', ['place' => $this->place])
    </x-filament::section>
</x-filament-widgets::widget>
