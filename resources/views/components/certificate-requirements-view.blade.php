@if ($this->getRequirements()->isEmpty())
    <div class="w-full">
        <div class="flex justify-center items-center">
            <div class="flex flex-col items-center">
                <div class="flex justify-center items-center">
                    <x-tabler-book-2 class="text-primary h-12 w-12" />
                </div>
                <p class="text-center text-gray-500 dark:text-gray-400 mt-2">
                    {{ __('learning/learningCertificate.custom.no_requirements_found') }}</p>
            </div>
        </div>
    </div>
@else
    <div class="w-full">
        <div class="">
            <ul class="flex flex-col gap-2 list-none">
                @foreach ($this->getRequirements() as $requirement)
                    <li class="md:flex justify-between w-full border-b border-gray-100 dark:border-gray-700 pb-2">
                        <div class="flex w-full items-center">
                            <div class="me-1.5">
                                @if ($this->checkCertificate($requirement->test_id))
                                    @if ($this->checkCertificate($requirement->test_id)->valid_to > now())
                                        <x-tabler-discount-check class="text-green-600 dark:text-green-400 h-6 w-6" />
                                    @else
                                        <x-tabler-exclamation-circle class="text-red-600 dark:text-red-400 h-6 w-6" />
                                    @endif
                                @else
                                    <x-tabler-book-2 class="text-primary dark:text-blue-400 h-6 w-6" />
                                @endif
                            </div>
                            <div class="md:flex justify-between w-full">
                                <div class="flex items-center">
                                    <div class="flex flex-col">
                                        <a href="{{ route('filament.app.resources.learning-tests.viewTest', ['record' => $requirement->test_id]) }}"
                                            class="group">
                                            <p
                                                class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300">
                                                <span class="inset-x-0 -top-px bottom-0"></span>
                                                @if ($place == 'dashboard')
                                                    {{ Str::limit($requirement->test->name, 100, '...') }}
                                                @else
                                                    {{ Str::limit($requirement->test->name, 100, '...') }}
                                                @endif
                                            </p>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-1 md:items-center" style="min-width: 174px;">
                                    <div class="flex sm:justify-start md:justify-end">
                                        <div class="flex text-center items-center">
                                            <div class="me-1" style="width: 24px;">
                                                <x-tabler-certificate class="text-gray-500 dark:text-gray-400" />
                                            </div>
                                            @if ($this->checkCertificate($requirement->test_id))
                                                @if ($this->checkCertificate($requirement->test_id)->valid_to > now())
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 me-2.5">
                                                        {{ __('learning/learningCertificate.custom.valid_until') }}
                                                        {{ $this->checkCertificate($requirement->test_id)->valid_to }}
                                                    </p>
                                                    <span class="relative flex justify-center mb-1">
                                                        <span
                                                            class="absolute h-5 w-5 rounded-full bg-green-200 dark:bg-green-700"></span>
                                                        <span
                                                            class="relative block h-3 w-3 rounded-full bg-green-600 dark:bg-green-400 hover-green-400 mt-1"></span>
                                                    </span>
                                                @else
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 me-2.5">
                                                        {{ __('learning/learningCertificate.custom.certificate_expired_at') }}
                                                        {{ $this->checkCertificate($requirement->test_id)->valid_to }}
                                                    </p>
                                                    <span class="relative flex justify-center mb-1">
                                                        <span
                                                            class="absolute h-5 w-5 rounded-full bg-red-200 dark:bg-red-700"></span>
                                                        <span
                                                            class="relative block h-3 w-3 rounded-full bg-red-600 dark:bg-red-400 hover-red-400 mt-1"></span>
                                                    </span>
                                                @endif
                                            @else
                                                <p class="text-sm text-gray-500 dark:text-gray-400 me-2.5">
                                                    {{ __('learning/learningCertificate.custom.certificate_missing') }}
                                                </p>
                                                <span class="relative flex justify-center mb-1">
                                                    <span
                                                        class="absolute h-5 w-5 rounded-full bg-red-200 dark:bg-red-700"></span>
                                                    <span
                                                        class="relative block h-3 w-3 rounded-full bg-red-600 dark:bg-red-400 hover-red-400 mt-1"></span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endif
