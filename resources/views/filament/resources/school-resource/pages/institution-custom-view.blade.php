<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 md:space-x-6">
        <div class="flex flex-col justify-center md:col-span-1 order-2 md:order-1">
            <div class="mb-2 flex items-center">
                <ul class="w-full text-2xl space-y-3">
                    <li class="flex flex-col sm:flex-row justify-between">
                        <p class="text-gray-600 dark:text-gray-300">Institute name:</p>
                        <p class="text-gray-950 font-bold dark:text-gray-100">{{ Str::limit($record->name, 16) }}</>
                    </li>
                    <li class="flex flex-col sm:flex-row justify-between">
                        <p class="text-gray-600 dark:text-gray-300">Country:</p>
                        <p class="text-gray-950 font-bold dark:text-gray-100">{{ Str::limit($record->country, 16) }}</p>
                    </li>
                    <li class="flex flex-col sm:flex-row justify-between">
                        <p class="text-gray-600 dark:text-gray-300">City:</p>
                        <p class="text-gray-950 font-bold dark:text-gray-100">{{ Str::limit($record->city, 16) }}</>
                    </li>
                    <li class="flex flex-col sm:flex-row justify-between">
                        <p class="text-gray-600 dark:text-gray-300">Address:</p>
                        <p class="text-gray-950 font-bold dark:text-gray-100">{{ Str::limit($record->address, 16) }}</>
                    </li>
                </ul>
            </div>

            <a href="{{ $record->website }}" class="mx-auto md:mx-0 hover:bg-blue-500 duration-300 w-40 items-center flex justify-center rounded-lg shadow-sm bg-blue-600 text-white px-4 py-2 hover:scale-105" target="_blank">
                <x-tabler-globe class="me-2"/>
                <span>Website</span>
            </a>
        </div>

        <div class="w-full md:cols-span-1 order-1 md:order-2 mb-4 md:mb-0">
            @if ($record->avatar)
                <img src="{{ asset($record->avatar) }}" alt="{{ $record->name }}" class="mx-auto rounded-lg shadow-sm max-h-80 object-contain">
            @else
                <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center shadow-sm">
                    <x-tabler-photo-off class="w-20 h-20 text-gray-600" />
                </div>
            @endif
        </div>
    </div>

    <div class="py-16 sm:py-20">
        <div class="mx-auto max-w-7xl px-6 md:px-8">
            <dl class="grid grid-cols-1 gap-x-8 gap-y-16 text-center md:grid-cols-3">
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">Users</dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ $record->users->count() }}</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">Groups</dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ $record->groups->count() }}</dd>
                </div>
                <div class="mx-auto flex max-w-xs flex-col gap-y-4">
                    <dt class="text-xl text-gray-600 dark:text-gray-300">Assigned certificates</dt>
                    <dd class="order-first font-semibold tracking-tight text-blue-600 text-5xl">
                        {{ $record->certificates->count() }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <div class="text-center">
        <p class="text-gray-600 dark:text-gray-300 text-xl font-semibold">{!! $record->description !!}</p>
    </div>
</div>
