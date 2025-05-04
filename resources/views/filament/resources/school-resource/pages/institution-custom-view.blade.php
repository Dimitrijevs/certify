<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <h1 class="text-gray-950 font-bold dark:text-gray-100 text-xl md:text-5xl text-center items-center mb-8">
        {{ Str::limit($record->name, 160) }}</h1>

    @if ($record->avatar)
        <img src="{{ asset($record->avatar) }}" alt="{{ $record->name }}" class="mx-auto rounded-lg mb-8">
    @else
        <div class="w-full h-80 bg-gray-200 rounded-lg flex items-center justify-center shadow-sm mb-8">
            <x-tabler-photo-off class="w-20 h-20 text-gray-600" />
        </div>
    @endif

    <div class="">
        <a href="{{ $record->website }}"
            class="mx-auto md:mx-0 hover:text-gray-600 duration-300 w-fit items-center flex justify-center rounded-lg shadow-sm border border-gray-200 bg-white hover:bg-gray-200 text-gray-900 px-3 py-2"
            target="_blank">
            <x-tabler-globe class="me-2" />
            <span>{{ __('institution.website') }}</span>
        </a>
    </div>

    <div class="pt-16 pb-8 sm:pt-20 sm:pb-8">
        <div class="mx-auto max-w-7xl px-6 md:px-8">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center md:grid-cols-3">
                <div class="mx-auto flex max-w-xs flex-col gap-y-4 relative" x-data="{ open: false }"
                    @mouseenter="open = true" @mouseleave="open = false">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">{{ __('institution.country') }}</dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ Str::limit($record->country, 12) }}</dd>
                    <div x-show="open"
                        class="absolute bottom-24 left-1/2 -translate-x-1/2 rounded-lg bg-white shadow-sm border border-gray-200 px-3 py-2 text-black whitespace-nowrap"
                        x-show="open">
                        {{ __('institution.country') }}: {{ $record->country }}
                    </div>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4 relative" x-data="{ open: false }"
                    @mouseenter="open = true" @mouseleave="open = false">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">{{ __('institution.city') }}</dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ Str::limit($record->city, 12) }}</dd>
                    <div x-show="open"
                        class="absolute bottom-24 left-1/2 -translate-x-1/2 rounded-lg bg-white shadow-sm border border-gray-200 px-3 py-2 text-black whitespace-nowrap"
                        x-show="open">
                        {{ __('institution.city') }}: {{ $record->city }}
                    </div>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4 relative" x-data="{ open: false }"
                    @mouseenter="open = true" @mouseleave="open = false">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">{{ __('institution.address') }}
                    </dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ Str::limit($record->address, 12) }}</dd>
                    <div x-show="open"
                        class="absolute bottom-24 left-1/2 -translate-x-1/2 rounded-lg bg-white shadow-sm border border-gray-200 px-3 py-2 text-black whitespace-nowrap"
                        x-show="open">
                        {{ __('institution.address') }}: {{ $record->address }}
                    </div>
                </div>
            </dl>
        </div>
    </div>

    <div class="pb-16 pt-8 sm:pb-20 sm:pt-8">
        <div class="mx-auto max-w-7xl px-6 md:px-8">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center md:grid-cols-3">
                <div class="mx-auto flex max-w-xs flex-col gap-y-4 relative" x-data="{ open: false }"
                    @mouseenter="open = true" @mouseleave="open = false">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">{{ __('institution.users') }}</dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ $record->users->count() }}</dd>
                    <div class="absolute bottom-24 left-1/2 -translate-x-1/2 rounded-lg bg-white shadow-sm border border-gray-200 px-3 py-2 text-black whitespace-nowrap"
                        x-show="open">
                        {{ $record->users->count() }} {{ __('institution.users') }}
                    </div>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4 relative" x-data="{ open: false }"
                    @mouseenter="open = true" @mouseleave="open = false">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">{{ __('institution.groups') }}</dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ $record->groups->count() }}</dd>
                    <div class="absolute bottom-24 left-1/2 -translate-x-1/2 rounded-lg bg-white shadow-sm border border-gray-200 px-3 py-2 text-black whitespace-nowrap"
                        x-show="open">
                        {{ $record->groups->count() }} {{ __('institution.groups') }}
                    </div>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4 relative" x-data="{ open: false }"
                    @mouseenter="open = true" @mouseleave="open = false">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">{{ __('institution.assigned_certificates') }}
                    </dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ $record->certificates->count() }}</dd>
                    <div class="absolute bottom-24 left-1/2 -translate-x-1/2 rounded-lg bg-white shadow-sm border border-gray-200 px-3 py-2 text-black whitespace-nowrap"
                        x-show="open">
                        {{ $record->certificates->count() }} {{ __('institution.assigned_certificates') }}
                    </div>
                </div>
            </dl>
        </div>
    </div>

    <div class="text-center">
        <p class="text-gray-600 dark:text-gray-300 text-xl font-semibold">{!! $record->description !!}</p>
    </div>
</div>
