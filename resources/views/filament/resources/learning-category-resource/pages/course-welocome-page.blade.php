<x-filament-panels::page>
    <div class="grid grid-cols-12 gap-4 items-start">
        <x-filament::section class="col-span-12 md:col-span-4 self-start">
            <x-slot name="heading">
                {{ __('welcome-course.course_general_information') }}
            </x-slot>

            <div class="space-y-6 mb-6">
                <x-welcome-page-list>
                    @slot('title')
                        {{ __('welcome-course.available_resources') }}
                    @endslot

                    @if ($this->availableResources()->count() > 0)
                        @slot('description')
                            @foreach ($this->availableResources() as $resource)
                                <x-welcome-page-list-item>
                                    {{ $resource->name }}
                                </x-welcome-page-list-item>
                            @endforeach
                        @endslot
                    @else
                        @slot('description')
                            <x-welcome-page-list-item>
                                Resources in development
                            </x-welcome-page-list-item>
                        @endslot
                    @endif
                </x-welcome-page-list>

                @if ($this->getCategories())
                    <x-welcome-page-list>
                        @slot('title')
                            {{ __('welcome-course.categories') }}
                        @endslot

                        @slot('description')
                            @foreach ($this->getCategories() as $category)
                                <x-welcome-page-list-item>
                                    {{ $category }}
                                </x-welcome-page-list-item>
                            @endforeach
                        @endslot
                    </x-welcome-page-list>
                @endif

                <x-welcome-page-list>
                    @slot('title')
                        {{ __('welcome-course.additional_information') }}
                    @endslot

                    @slot('description')
                        <x-welcome-page-list-item>
                            {{ __('welcome-course.language') }}: {{ $record->language->name }}
                        </x-welcome-page-list-item>
                        <x-welcome-page-list-item>
                            {{ __('welcome-course.created_at') }}: {{ $record->created_at->format('d M Y') }}
                        </x-welcome-page-list-item>
                        <x-welcome-page-list-item>
                            {{ __('welcome-course.last_time_updated_at') }}: {{ $record->updated_at->format('d M Y') }}
                        </x-welcome-page-list-item>
                        <x-welcome-page-list-item>
                            {{ __('welcome-course.students_enrolled') }}: {{ $purchasesCount }}
                        </x-welcome-page-list-item>
                        <x-welcome-page-list-item>
                            {{ __('welcome-course.created_by') }}: {{ $record->createdBy->name }}
                        </x-welcome-page-list-item>
                    @endslot
                </x-welcome-page-list>
            </div>

            {{-- When auth user had bought or created by auth user --}}
            @if ($this->getFirstResourceId() && ($this->checkUserPurchase() || Auth::id() == $record->created_by))
                <a
                    href="{{ route('filament.app.resources.learning-categories.resource', ['record' => $this->getFirstResourceId()]) }}">
                    <x-cyan-button>
                        {{ __('welcome-course.continue_learning') }}
                    </x-cyan-button>
                </a>
            @elseif (!$this->checkUserPurchase() && $this->getTotalPrice() > 0)
                {{-- When user not bought and price is greater than 0 --}}
                <a
                    href="{{ route('filament.app.pages.purchase-page.{type?}.{product_id?}', ['type' => 'course', 'product_id' => $record->id]) }}">
                    <x-cyan-button>
                        @if ($this->getTotalPrice() > 0 && $record->discount > 0)
                            {{ __('welcome-course.buy_now') }} ( {{ $record->price }} - {{ $record->discount }}% =
                            {{ number_format($this->getTotalPrice(), 2) }}
                            {{ $record->currency->symbol }})
                        @elseif ($this->getTotalPrice() > 0 && $record->discount == 0)
                            {{ __('welcome-course.buy_now') }} ( {{ number_format($this->getTotalPrice(), 2) }}
                            {{ $record->currency->symbol }})
                        @endif
                    </x-cyan-button>
                </a>
            @elseif (!$this->checkUserPurchase() && $this->getTotalPrice() == 0)
                {{-- When user not bought and price is 0 --}}
                <form action="{{ route('complete.purchase', $record->created_by) }}" method="POST" class="w-full">
                    @csrf

                    <input type="hidden" name="lang" value="{{ $lang }}">
                    <input type="hidden" name="course_id" value="{{ $record->id }}">

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
        </x-filament::section>

        <x-filament::section class="col-span-12 md:col-span-8 self-start">
            <x-slot name="heading">
                {{ __('welcome-course.course_description') }}
            </x-slot>

            <div class="w-full mb-4">
                @if ($record->thumbnail)
                    <img src="{{ asset($record->thumbnail) }}" class="rounded-md" alt="Cover Image"
                        style="height: 400px; width: 100%; object-fit: cover;">
                @else
                    <div class="aspect-w-16 aspect-h-9 w-full rounded-lg bg-gray-200 flex justify-center items-center"
                        style="height: 400px">
                        <span class="text-lg text-gray-600"><x-tabler-photo-off class="w-12 h-12" /></span>
                    </div>
                @endif
            </div>

            <div class="">
                <p>{!! $record->description !!}</p>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
