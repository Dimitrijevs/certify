<x-filament-panels::page>
    <div class="grid lg:grid-cols-12 md:grid-cols-12 gap-4">
        <div class="lg:col-span-4 md:col-span-4 order-2 lg:order-1 md:order-1 space-y-4">
            <x-filament::section>
                <x-slot name="heading">
                    {{ __('learning/learningCategory.label_plural') }}
                </x-slot>

                <ul class="space-y-4">
                    @if ($record->category_id)
                        @foreach ($record->category_id as $id)
                            <li>
                                <a
                                    href="{{ route('filament.app.resources.learning-categories.course-welcome-page', ['record' => $id]) }}">
                                    <span class="flex items-start">
                                        <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                            <span
                                                class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-700"></span>
                                            <span
                                                class="relative block h-3 w-3 rounded-full bg-blue-500 dark:bg-blue-300"></span>
                                        </span>
                                        <span
                                            class="ml-4 text-md font-medium text-gray-500 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100">
                                            {{ $this->getCategoryName($id) }}
                                        </span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    @else
                        <li>
                            <span class="flex items-start">
                                <x-tabler-note-off class="text-gray-600 dark:text-gray-400" />
                                <span
                                    class="ml-4 text-md font-medium text-gray-500 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100">
                                    {{ __('learning/learningTest.form.no_selected_courses') }}
                                </span>
                            </span>
                        </li>
                    @endif
                </ul>
            </x-filament::section>

            <x-filament::section>
                <x-slot name="heading">
                    {{ __('learning/learningTest.fields.test_general_information') }}
                </x-slot>

                <div class="space-y-6">
                    @if ($this->getCategoriesNames())
                        <x-welcome-page-list>
                            @slot('title')
                                {{ __('learning/learningTest.fields.categories') }}
                            @endslot

                            @slot('description')
                                @foreach ($this->getCategoriesNames() as $category)
                                    <x-welcome-page-list-item>
                                        {{ $category }}
                                    </x-welcome-page-list-item>
                                @endforeach
                            @endslot
                        </x-welcome-page-list>
                    @endif

                    <x-welcome-page-list>
                        @slot('title')
                            {{ __('learning/learningTest.fields.additional_information') }}
                        @endslot

                        @slot('description')
                            <x-welcome-page-list-item>
                                {{ __('learning/learningTest.fields.language') }}:
                                {{ $record->language->name }}
                            </x-welcome-page-list-item>
                            <x-welcome-page-list-item>
                                {{ __('learning/learningTest.fields.created_at') }}:
                                {{ $record->created_at->format('d M Y') }}
                            </x-welcome-page-list-item>
                            <x-welcome-page-list-item>
                                {{ __('learning/learningTest.fields.last_time_updated_at') }}:
                                {{ $record->updated_at->format('d M Y') }}
                            </x-welcome-page-list-item>
                            <x-welcome-page-list-item>
                                {{ __('learning/learningTest.fields.students_enrolled') }}: {{ $purchasesCount }}
                            </x-welcome-page-list-item>
                            <x-welcome-page-list-item>
                                {{ __('learning/learningTest.fields.created_by') }}: {{ $record->createdBy->name }}
                            </x-welcome-page-list-item>
                        @endslot
                    </x-welcome-page-list>
                </div>
            </x-filament::section>
        </div>

        <div class="lg:col-span-8 md:col-span-8 order-1 lg:order-2 md:order-2">
            <x-filament::section>
                <x-slot name="heading">
                    {{ __('learning/learningTest.custom.test_information') }}
                </x-slot>

                <div class="mb-5">
                    <div class="">
                        <div class="flex items-center mb-1">
                            <h2 class="font-bold tracking-tight text-black dark:text-white text-2xl">
                                {{ $record->name }}
                            </h2>
                        </div>

                        <div class="my-3 grid grid-cols-1 gap-4 lg:grid-cols-1 xl:grid-cols-3">
                            <div
                                class="relative overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute rounded-md bg-blue-500 p-3">
                                        <x-tabler-question-mark class="text-white" />
                                    </div>
                                    <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('learning/learningTest.custom.questions') }}</p>
                                </dt>
                                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                        @if ($record->details->count() > 0)
                                            {{ $record->details->where('is_active', true)->count() }}
                                        @else
                                            {{ __('learning/learningTest.custom.n_a') }}
                                        @endif
                                    </p>
                                </dd>
                            </div>

                            <div
                                class="relative overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute rounded-md bg-blue-500 p-3">
                                        <x-tabler-award class="text-white" />
                                    </div>
                                    <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('learning/learningTest.custom.min_score') }}</p>
                                </dt>
                                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                        @if ($this->record->details->where('is_active', true)->sum('points') <= $this->record->min_score)
                                            0
                                        @else
                                            {{ $this->record->details->where('is_active', true)->sum('points') - $this->record->min_score }}
                                        @endif
                                        / {{ $this->record->details->where('is_active', true)->sum('points') }}
                                    </p>
                                </dd>
                            </div>

                            <div
                                class="relative overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pt-5 shadow sm:px-6 sm:pt-6">
                                <dt>
                                    <div class="absolute rounded-md bg-blue-500 p-3">
                                        <x-tabler-clock class="text-white" />
                                    </div>
                                    <p class="ml-16 truncate text-sm font-medium text-gray-500 dark:text-gray-300">
                                        {{ __('learning/learningTest.fields.time_limit') }}</p>
                                </dt>
                                <dd class="ml-16 flex items-baseline pb-6 sm:pb-7">
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                        @if (!is_null($record->time_limit))
                                            {{ $record->time_limit }} {{ __('learning/learningTest.fields.minutes') }}
                                        @else
                                            {{ __('learning/learningTest.custom.n_a') }}
                                        @endif
                                    </p>
                                </dd>
                            </div>
                        </div>

                        <span class="mb-2 text-gray-900 dark:text-gray-100 text-md">{!! $record->description !!}</span>
                    </div>
                </div>

                <div class="text-center">
                    {{-- when auth user bought a test or auth user is a creator --}}
                    @if ($this->checkUserPurchase() || Auth::id() == $record->created_by)
                        <x-filament::modal id="start-test">
                            <x-slot name="trigger">
                                <x-cyan-button>
                                    <div class="flex items-center">
                                        <x-tabler-player-play class="me-1" />
                                        {{ __('learning/learningTest.custom.start_test') }}
                                    </div>
                                </x-cyan-button>
                            </x-slot>

                            <x-tabler-exclamation-circle class="mx-auto text-red-500 w-16 h-16" />

                            <h3
                                class="fi-section-header-heading text-lg font-bold leading-6 text-gray-950 dark:text-white mb-0 text-center">
                                {{ __('learning/learningTest.custom.start_test') }}
                            </h3>

                            <p class="text-base leading-6 text-gray-950 dark:text-white mb-2 text-center opacity-60">
                                {{ __('learning/learningTest.custom.are_you_sure_you_want_to_start_the_test') }}?
                            </p>

                            <div class="flex text-center justify-between space-x-4">
                                <x-filament::button x-on:click="$dispatch('close-modal', { id: 'start-test' })"
                                    class="w-1/2" color="danger">
                                    {{ __('learning/learningTest.custom.no') }}
                                </x-filament::button>

                                @if ($this->record->details->where('is_active', true)->count() == 0)
                                    <x-filament::button color="primary" class="w-1/2" disabled>
                                        {{ __('learning/learningTest.custom.yes') }}
                                    </x-filament::button>
                                @elseif (!$this->cooldownFinished())
                                    <x-filament::button color="primary" class="w-1/2" disabled>
                                        {{ __('learning/learningTest.custom.yes') }}
                                    </x-filament::button>
                                @elseif ($this->cooldownFinished())
                                    <a class="w-1/2"
                                        href="{{ route('filament.app.resources.learning-test-results.do-test', ['record' => $record->id, 'question' => 1, 'viewTest' => 0]) }}">
                                        <x-filament::button color="primary" class="w-full">
                                            {{ __('learning/learningTest.custom.yes') }}
                                        </x-filament::button>
                                    </a>
                                @else
                                    <a class="w-1/2"
                                        href="{{ route('filament.app.resources.learning-test-results.do-test', ['record' => $record->id, 'question' => 1, 'viewTest' => 0]) }}">
                                        <x-filament::button color="primary" class="w-full">
                                            {{ __('learning/learningTest.custom.yes') }}
                                        </x-filament::button>
                                    </a>
                                @endif
                            </div>
                        </x-filament::modal>
                    @elseif (!$this->checkUserPurchase() && $this->getTotalPrice() > 0)
                        {{-- When user not bought and price is greater than 0 --}}
                        <a
                            href="{{ route('filament.app.pages.purchase-page.{type?}.{product_id?}', ['type' => 'test', 'product_id' => $record->id]) }}">
                            <x-cyan-button>
                                @if ($this->getTotalPrice() > 0 && $record->discount > 0)
                                    {{ __('learning/learningTest.fields.buy_now') }} ( {{ $record->price }} -
                                    {{ $record->discount }}% =
                                    {{ number_format($this->getTotalPrice(), 2) }}
                                    {{ $record->currency->symbol }})
                                @elseif ($this->getTotalPrice() > 0 && $record->discount == 0)
                                    {{ __('learning/learningTest.fields.buy_now') }} (
                                    {{ number_format($this->getTotalPrice(), 2) }}
                                    {{ $record->currency->symbol }})
                                @endif
                            </x-cyan-button>
                        </a>
                    @elseif (!$this->checkUserPurchase() && $this->getTotalPrice() == 0)
                        {{-- When user not bought and price is 0 --}}
                        <form action="{{ route('complete.purchase', $record->created_by) }}" method="POST"
                            class="w-full">
                            @csrf

                            <input type="hidden" name="test_id" value="{{ $record->id }}">

                            <x-cyan-button>
                                {{ __('welcome-course.enroll_now_for_free') }}
                            </x-cyan-button>
                        </form>
                    @else
                        {{-- When an error occurred --}}
                        <x-cyan-button disabled>
                            {{ __('welcome-course.something_went_wrong') }}
                        </x-cyan-button>
                    @endif
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>
