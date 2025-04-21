<x-filament-panels::page>
    <div class="grid grid-cols-12 gap-4 items-start">
        <x-filament::section class="col-span-12 md:col-span-4 self-start">
            <x-slot name="heading">
                Course Information
            </x-slot>

            <div class="space-y-6 mb-6">
                <x-welcome-page-list>
                    @slot('title')
                        Available Resources
                    @endslot

                    @slot('description')
                        @foreach ($this->availableResources() as $resource)
                            <x-welcome-page-list-item>
                                {{ $resource->name }}
                            </x-welcome-page-list-item>
                        @endforeach
                    @endslot
                </x-welcome-page-list>

                @if ($this->getCategories())
                    <x-welcome-page-list>
                        @slot('title')
                            Categories
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
                        Additional Information
                    @endslot

                    @slot('description')
                        <x-welcome-page-list-item>
                            Created At: {{ $record->created_at->format('d M Y') }}
                        </x-welcome-page-list-item>
                        <x-welcome-page-list-item>
                            Last Time Updated At: {{ $record->updated_at->format('d M Y') }}
                        </x-welcome-page-list-item>
                        <x-welcome-page-list-item>
                            Students Enrolled: {{ $purchasesCount }}
                        </x-welcome-page-list-item>
                        <x-welcome-page-list-item>
                            Created By: {{ $record->createdBy->name }}
                        </x-welcome-page-list-item>
                    @endslot
                </x-welcome-page-list>
            </div>

            @if (!$this->checkUserPurchase() && $this->getTotalPrice() > 0)
                <form action="{{ route('filament.app.pages.purchase-page') }}" method="GET">
                    @csrf

                    <input type="hidden" name="seller_id" value="{{ $record->created_by }}">
                    <input type="hidden" name="price" value="{{ $this->getTotalPrice() }}">
                    <input type="hidden" name="course_id" value="{{ $record->id }}">

                    <x-cyan-button>
                        @if ($this->getTotalPrice() > 0 && $record->discount > 0)
                            Buy Now ( {{ $record->price }} - {{ $record->discount }}% =
                            {{ number_format($this->getTotalPrice(), 2) }}
                            {{ $record->currency->symbol }})
                        @elseif ($this->getTotalPrice() > 0 && $record->discount == 0)
                            Buy Now ( {{ number_format($this->getTotalPrice(), 2) }}
                            {{ $record->currency->symbol }})
                        @endif
                    </x-cyan-button>
                </form>
            @elseif (!$this->checkUserPurchase() && $this->getTotalPrice() == 0)
                <form action="{{ route('complete.purchase', ['id' => $record->created_by]) }}" method="POST">
                    @csrf

                    <input type="hidden" name="price" value="0">
                    <input type="hidden" name="course_id" value="{{ $record->id }}">

                    <x-cyan-button>
                        Enroll Now For Free
                    </x-cyan-button>
                </form>
            @else
                <a
                    href="{{ route('filament.app.resources.learning-categories.resource', ['record' => $this->getFirstResourceId()]) }}">
                    <x-cyan-button>
                        Continue Learning
                    </x-cyan-button>
                </a>
            @endif
        </x-filament::section>

        <x-filament::section class="col-span-12 md:col-span-8 self-start">
            <x-slot name="heading">
                Course Information
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
