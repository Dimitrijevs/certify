<x-filament-panels::page>
    <div class="grid grid-cols-12 gap-4 items-start">
        <x-filament::section class="col-span-12 md:col-span-4 self-start">
            <x-slot name="heading">
                Course Information
            </x-slot>

            <div class="space-y-6 mb-6">
                <div class="">
                    <p class="mb-4 font-bold">Available Resources</p>
                    <ul class="space-y-4">
                        @foreach ($this->userActivity() as $resource)
                            <li class="group">
                                <span class="flex items-start">
                                    <x-circle-bullet/>
                                    <span class="ml-3 text-md font-medium text-gray-900 dark:text-white">
                                        {{ $resource->name }}
                                    </span>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="space-y-4">
                    <p class="font-bold">Also Included</p>
                    <ul class="space-y-4">
                        <li class="group">
                            <span class="flex items-start">
                                <x-circle-bullet/>
                                <span class="ml-3 text-md font-medium text-gray-900 dark:text-white">
                                    Certificate of Completion
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <p class="font-bold">Additional Information</p>
                    <ul class="space-y-4">
                        <li class="group">
                            <span class="flex items-start">
                                <x-circle-bullet/>
                                <span class="ml-3 text-md font-medium text-gray-900 dark:text-white">
                                    Created At: {{ $record->created_at->format('d M Y') }}
                                </span>
                            </span>
                        </li>
                        <li class="group">
                            <span class="flex items-start">
                                <x-circle-bullet/>
                                <span class="ml-3 text-md font-medium text-gray-900 dark:text-white">
                                    Last Time Updated At: {{ $record->updated_at->format('d M Y') }}
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <form action="{{ route('filament.app.pages.purchase-page') }}" method="GET">
                @csrf

                <input type="hidden" name="seller_id" value="{{ $record->created_by }}">
                <input type="hidden" name="price" value="{{ $this->getTotalPrice() }}">
                <input type="hidden" name="course_id" value="{{ $record->id }}">

                <x-cyan-button>
                        @if ($this->getTotalPrice() > 0 && $record->discount > 0)
                            Buy Now ( {{ $record->price }} - {{ $record->discount }}% =
                            {{ number_format($this->getTotalPrice(), 2) }}
                            {{ $record->currency }})
                        @elseif ($this->getTotalPrice() > 0 && $record->discount == 0)
                            Buy Now ( {{ number_format($this->getTotalPrice(), 2) }}
                            {{ $record->currency }})
                        @else
                            Enroll Now For Free
                        @endif
                </x-cyan-button>
            </form>
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
