<x-filament-panels::page>
    <div class="grid grid-cols-12 space-x-4">
        <x-filament::section class="col-span-12 md:col-span-4">
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
                                    <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                        <span class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-700"></span>
                                        <span
                                            class="relative block h-3 w-3 rounded-full bg-blue-500 dark:bg-blue-300"></span>
                                    </span>
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
                                <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                    <span class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-700"></span>
                                    <span
                                        class="relative block h-3 w-3 rounded-full bg-blue-500 dark:bg-blue-300"></span>
                                </span>
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
                                <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                    <span class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-700"></span>
                                    <span
                                        class="relative block h-3 w-3 rounded-full bg-blue-500 dark:bg-blue-300"></span>
                                </span>
                                <span class="ml-3 text-md font-medium text-gray-900 dark:text-white">
                                    Created At: {{ $record->created_at->format('d M Y') }}
                                </span>
                            </span>
                        </li>
                        <li class="group">
                            <span class="flex items-start">
                                <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                    <span class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-700"></span>
                                    <span
                                        class="relative block h-3 w-3 rounded-full bg-blue-500 dark:bg-blue-300"></span>
                                </span>
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

                <button type="submit">
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
                </button>
            </form>
        </x-filament::section>
    </div>
</x-filament-panels::page>
