<style>
    .outer-container {
        height: 100vh;
    }

    @media (max-width: 900px) {
        .outer-container {
            height: 90vh;
        }
    }

    @media (max-width: 640px) {
        .outer-container {
            height: 85vh;
        }
    }

    @media (max-width: 430px) {
        .outer-container {
            height: 75vh;
        }
    }
</style>

<x-filament-panels::page>
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <div class="cols-span-1 order-2 xl:order-1">
            <x-filament::section>
                <x-slot name="heading">
                    {{ __('learning/learningCertificate.custom.details_section') }}
                </x-slot>

                <dl class="flex flex-col">
                    <a href="{{ route('filament.app.resources.learning-tests.viewTest', ['record' => $this->record->test->id]) }}"
                        class="group">
                        <div class="flex-auto mb-3">
                            @if ($this->record->test->thumbnail)
                                <img src="{{ asset($this->record->test->thumbnail) }}"
                                    class="rounded-lg group-hover:opacity-90" alt=""
                                    style="height: 200px; width: 100%; object-fit: cover;">
                            @else
                                <div class="rounded-lg bg-gray-200 group-hover:opacity-90 flex justify-center items-center"
                                    style="height: 200px; width: 100%;">
                                    <span class="text-lg text-gray-600 dark:text-gray-300"><x-tabler-photo-off
                                            class="w-12 h-12" /></span>
                                </div>
                            @endif
                            <dd
                                class="mt-3 text-base font-semibold text-gray-950 dark:text-gray-100 group-hover:opacity-60">
                                {{ $this->record->test->name }}</dd>

                            <dd class="text-sm text-gray-950 dark:text-gray-100 group-hover:opacity-60">
                                <div class="mt-1">{!! Str::limit($this->record->test->description, 120) !!}</div>
                            </dd>
                        </div>
                    </a>
                    @if ($this->record->completedTest)
                        <a href="{{ route('filament.app.resources.learning-test-results.do-test', ['record' => $this->record->completedTest->id, 'question' => 1, 'viewTest' => 1]) }}"
                            class="group hover:opacity-60">
                            <div class="mb-3 pt-3 flex w-full flex-none gap-x-4 border-t border-gray-900/5">
                                <dt class="flex-none">
                                    <x-tabler-award class="text-gray-900 dark:text-gray-100" />
                                </dt>
                                <dd class="text-base font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('learning/learningCertificate.custom.points') }}:
                                    {{ $this->record->completedTest->points }} /
                                    {{ $this->record->test->details->where('is_active', true)->sum('points') }}
                                </dd>
                            </div>
                            <div class="pb-3 flex w-full flex-none gap-x-4">
                                <dt class="flex-none">
                                    <x-tabler-calendar-event class="text-gray-900 dark:text-gray-100" />
                                </dt>
                                <dd class="text-base text-gray-900 dark:text-gray-100">
                                    <time
                                        datetime="2023-01-31">{{ __('learning/learningCertificate.custom.completed_at') }}:
                                        {{ \Carbon\Carbon::parse($this->record->completedTest->finished_at)->format('Y-m-d') }}</time>
                                </dd>
                            </div>
                            <div class="pb-3 flex w-full flex-none gap-x-4">
                                <dt class="flex-none">
                                    <x-tabler-clock class="text-gray-900 dark:text-gray-100" />
                                </dt>
                                <dd class="text-base text-gray-900 dark:text-gray-100">
                                    {{ __('learning/learningCertificate.custom.time_spent') }}:
                                    {{ \Carbon\Carbon::parse($this->record->completedTest->total_time)->format('H:i:s') }}
                                </dd>
                            </div>
                            <div
                                class="{{ $this->record->description ? 'pb-2' : 'pb-3' }} flex w-full flex-none gap-x-4">
                                <dt class="flex-none">
                                    @if ($this->isValid())
                                        @if (is_null($this->record->valid_to))
                                            <x-tabler-certificate class="text-green-600 dark:text-green-400" />
                                        @else
                                            <x-tabler-certificate class="text-green-600 dark:text-green-400" />
                                        @endif
                                    @else
                                        <x-tabler-certificate class="text-red-600 dark:text-red-400" />
                                    @endif
                                </dt>
                                @if ($this->isValid())
                                    <dd class="text-base text-green-600 dark:text-green-400">
                                        {{ __('learning/learningCertificate.fields.valid_untill') }}:
                                        {{ $this->record->valid_to }}</dd>
                                @else
                                    <dd class="text-base text-red-600 dark:text-red-400">
                                        {{ __('learning/learningCertificate.custom.invalid_certificate') }}</dd>
                                @endif
                            </div>
                        </a>
                    @else
                        <div class="flex-auto border-t border-gray-900/5 pt-3">
                            <div
                                class="{{ $this->record->description ? 'pb-2' : 'pb-3' }} flex w-full flex-none gap-x-4">
                                <dt class="flex-none">
                                    @if ($this->isValid())
                                        <x-tabler-certificate class="text-green-600 dark:text-green-400" />
                                    @else
                                        <x-tabler-certificate class="text-red-600 dark:text-red-400" />
                                    @endif
                                </dt>
                                @if ($this->isValid())
                                    @if (is_null($this->record->valid_to))
                                        <dd class="text-base text-green-600 dark:text-green-400">
                                            {{ __('learning/learningCertificate') }}</dd>
                                    @else
                                        <dd class="text-base text-green-600 dark:text-green-400">
                                            {{ __('learning/learningCertificate.fields.valid_untill') }}:
                                            {{ $this->record->valid_to }}</dd>
                                    @endif
                                @else
                                    <dd class="text-base text-red-600 dark:text-red-400">
                                        {{ __('learning/learningCertificate.custom.invalid_certificate') }}</dd>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if ($this->record->description)
                        <div class="pb-3">
                            <h3 class="text-md font-semibold">
                                {{ __('learning/learningCertificate.fields.description') }}
                            </h3>
                            <p class="text-sm">{!! Str::limit($this->record->description, 120) !!}</p>
                        </div>
                    @endif

                    <div class="border-t border-gray-900/5 pt-3">
                        <h3 class="text-base font-semibold mb-1">
                            {{ __('learning/learningCertificate.custom.created_by') }}
                        </h3>
                        @if (Auth::user()->role_id < 4)
                            <a class="group"
                                href="{{ route('filament.app.resources.users.edit', ['record' => $this->record->admin_id]) }}">
                        @endif
                        <div class="flex items-center gap-2 pe-3">
                            @if (is_null($this->record->admin->avatar))
                                <div
                                    class="rounded-full overflow-hidden h-9 w-9 flex items-center justify-center group-hover:opacity-80 border border-gray-200 dark:border-gray-700">
                                    @svg('tabler-user', 'text-gray-400 dark:text-gray-500 h-6 w-6')
                                </div>
                            @else
                                <div
                                    class="rounded-full overflow-hidden h-9 w-9 flex items-center justify-center group-hover:opacity-80">
                                    <img src="{{ asset($this->record->admin->avatar) }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex flex-col justify-center mb-1">
                                <p
                                    class="text-sm text-gray-900 dark:text-gray-100 group-hover:text-gray-500 dark:group-hover:text-gray-400">
                                    {{ Str::limit($this->record->admin->name, 26) }}</p>
                                <p
                                    class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-400 dark:group-hover:text-gray-500">
                                    {{ Str::limit($this->record->admin->job_title, 36) }}</p>
                            </div>
                        </div>
                        @if (Auth::user()->role_id < 4)
                            </a>
                        @endif
                    </div>
                </dl>
            </x-filament::section>
        </div>

        <div class="col-span-2 order-1 xl:order-2">
            <x-filament::section>
                <x-slot name="heading">
                    {{ $this->record->name }}
                </x-slot>

                @if ($this->record->thumbnail)
                    @if ($this->getThumbnailType() === 'image')
                        <div class="flex justify-center items-center">
                            <img src="{{ asset($this->record->thumbnail) }}" class="rounded-lg" alt=""
                                style="height: 340px;">
                        </div>
                    @elseif ($this->getThumbnailType() === 'pdf')
                        <div class="outer-container h-screen">
                            <div class="flex justify-center items-center w-full h-full">
                                <embed src="{{ asset($this->record->thumbnail) }}" type="application/pdf"
                                    class="w-full h-full max-w-full max-h-full">
                            </div>
                        </div>
                    @else
                        <div class="flex justify-center items-center">
                            <a href="{{ asset($this->record->thumbnail) }}" class="text-blue-500 underline"
                                download>{{ __('learning/learningCertificate.custom.download') }}</a>
                        </div>
                    @endif
                @else
                    <div class="outer-container h-screen">
                        <div class="flex justify-center items-center w-full h-full">
                            <embed
                                src="{{ route('learning-resources.pdf', ['learningResource' => $this->record->id, 'isDownload' => 0]) }}"
                                type="application/pdf" class="w-full h-full">
                        </div>
                    </div>
                @endif

                @if (!is_null($this->record->description))
                    <div class="mt-5">
                        <h3 class="text-lg font-semibold">{{ __('learning/learningCertificate.fields.description') }}
                        </h3>
                        <p class="mt-1">{!! $this->record->description !!}</p>
                    </div>
                @endif
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
