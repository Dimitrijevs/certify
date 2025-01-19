<x-filament-panels::page>
    <div class="grid grid-cols-3 gap-4">
        <div class="order-2 xl:order-1 col-span-3 xl:col-span-1">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex justify-between items-center">
                        <p class="py-1">{{ __('learning/learningTestResult.custom.test_navigation') }}</p>

                        @if (!$this->view_test && $this->record->time_limit)
                            <p
                                class="ml-2 px-2 py-1 text-black border border-gray-200 dark:border-gray-600 rounded-md flex items-center opacity-70">
                                <x-tabler-clock class="text-gray-950 dark:text-gray-400 mr-1" />
                                <span id="time-left">{{ $this->getInitialTimeLeft() }}</span>
                            </p>
                        @endif
                    </div>
                </x-slot>

                <ul class="{{ $this->view_test ? 'border-b border-gray-100 pb-1' : '' }}">
                    @foreach ($this->getQuestions() as $index => $question)
                        @if ($this->transition_enabled)
                            <a class="group"
                                @if ($this->view_test) href="{{ route('filament.app.resources.learning-test-results.do-test', ['record' => $this->result->id, 'question' => $index + 1, 'viewTest' => 1]) }}">
                                @else
                                    href="{{ route('filament.app.resources.learning-test-results.do-test', ['record' => $this->record->id, 'question' => $index + 1, 'viewTest' => 0]) }}"> @endif
                                <li class="flex items-center justify-between pb-3">
                                <div class="flex">
                                    @if ($this->view_test)
                                        @if ($this->isAnswerCorrect($this->getUserAnswer($index)?->user_answer, $this->getCorrectAnswer($question->id)))
                                            <span class="rounded-lg p-1 opacity-90 ms-2 bg-green-600 dark:bg-green-700">
                                                <x-tabler-check class="text-white h-4 w-4" />
                                            </span>
                                        @else
                                            <span class="rounded-lg p-1 opacity-90 ms-2 bg-red-600 dark:bg-red-700">
                                                <x-tabler-x class="text-white h-4 w-4" />
                                            </span>
                                        @endif
                                    @else
                                        <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                            @if ($index == $this->position)
                                                <span
                                                    class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-900"></span>
                                                <span
                                                    class="relative block h-3 w-3 rounded-full bg-blue-600 group-hover:bg-blue-400"></span>
                                            @elseif ($this->getUserAnswer($index))
                                                <div class="bg-blue-600 group-hover:bg-blue-400 rounded-full p-0.5">
                                                    <x-tabler-check class="h-full w-full text-white" />
                                                </div>
                                            @else
                                                <div
                                                    class="h-3 w-3 rounded-full bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400">
                                                </div>
                                            @endif
                                        </span>
                                    @endif
                                    @if ($index == $this->position)
                                        <span
                                            class="ml-2 text-md text-blue-600 dark:text-blue-400 group-hover:text-blue-400">{{ $index + 1 }}.
                                            {{ __('learning/learningTestResult.custom.question') }}</span>
                                    @else
                                        <span
                                            class="ml-2 text-md font-medium text-gray-500 dark:text-gray-400 group-hover:text-blue-400">{{ $index + 1 }}.
                                            {{ __('learning/learningTestResult.custom.question') }}</span>
                                    @endif
                                </div>

                                @if ($this->view_test)
                                    <div class="flex">
                                        <div
                                            class="ml-2 px-2 py-1 text-black dark:text-white border border-gray-200 dark:border-gray-600 rounded-md flex opacity-70">
                                            <x-tabler-award class="text-gray-950 dark:text-gray-400 mr-1" />
                                            <span class="">
                                                @if ($this->view_test)
                                                    @if ($this->getUserAnswer($index))
                                                        {{ $this->getUserAnswer($index)?->points }}
                                                        /
                                                    @else
                                                        0 /
                                                    @endif
                                                @endif

                                                {{ $question->points }}
                                                {{ __('learning/learningTestResult.custom.points') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                </li>
                            </a>
                        @else
                            <li class="mb-4 flex items-start">
                                <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                    @if ($index == $this->position)
                                        <span class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-900"></span>
                                        <span class="relative block h-3 w-3 rounded-full bg-blue-600"></span>
                                    @elseif ($this->getUserAnswer($index))
                                        <svg class="h-full w-full text-blue-500 dark:text-blue-400 group-hover:text-blue-800"
                                            viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    @else
                                        <div
                                            class="h-3 w-3 rounded-full bg-gray-300 dark:bg-gray-600 group-hover:bg-gray-400">
                                        </div>
                                    @endif
                                </span>
                                @if ($index == $this->position)
                                    <span class="ml-4 text-md text-blue-500 dark:text-blue-300">{{ $index + 1 }}.
                                        {{ __('learning/learningTestResult.custom.question') }}</span>
                                @else
                                    <span
                                        class="ml-4 text-md font-medium text-gray-500 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200">{{ $index + 1 }}.
                                        {{ __('learning/learningTestResult.custom.question') }}</span>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>

                @if ($this->view_test)
                    <div class="flex justify-start mt-4">
                        <p class="px-2 py-1 text-black dark:text-white border rounded-md flex items-center opacity-80"
                            style="border-color:{{ $this->result->is_passed ? '#00C9A7;' : '#ed4c78;' }}">
                            <x-tabler-award
                                class="mr-1 {{ $this->result->is_passed ? 'text-green-600' : 'text-red-600' }}" />
                            <span
                                class="{{ $this->result->is_passed ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ __('learning/learningTestResult.custom.total_points') }}:
                                {{ $this->result->details->sum('points') }}
                                /
                                {{ $this->getSumOfQuestionPoints() }}</span>
                        </p>
                    </div>

                    <div class="mt-2">
                        <h3 class="text-base font-semibold mb-1">{{ __('employee.label') }}
                        </h3>
                        @if (Auth::user()->can('update_user'))
                            <a class="group"
                                href="{{ route('filament.app.resources.employees.edit', ['record' => $this->result->user_id]) }}">
                        @endif
                        <div class="flex items-center gap-2 pe-3">
                            @if (is_null($this->result->user->avatar))
                                <div
                                    class="rounded-full overflow-hidden h-9 w-9 flex items-center justify-center group-hover:opacity-80 border border-gray-200 dark:border-gray-700">
                                    @svg('tabler-user', 'text-gray-400 dark:text-gray-500 h-6 w-6')
                                </div>
                            @else
                                <div
                                    class="rounded-full overflow-hidden h-9 w-9 flex items-center justify-center group-hover:opacity-80">
                                    <img src="{{ asset($this->result->user->avatar) }}"
                                        class="w-full h-full object-cover">
                                </div>
                            @endif
                            <div class="flex flex-col justify-center mb-1">
                                <p
                                    class="text-sm text-gray-900 dark:text-gray-100 group-hover:text-gray-500 dark:group-hover:text-gray-400">
                                    {{ Str::limit($this->result->user->name, 26) }}</p>
                                <p
                                    class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-400 dark:group-hover:text-gray-500">
                                    {{ Str::limit($this->result->user->job_title, 36) }}</p>
                            </div>
                        </div>
                        @if (Auth::user()->can('update_user'))
                            </a>
                        @endif
                    </div>
                @endif
            </x-filament::section>
        </div>

        <div class="order-1 xl:order-2 col-span-3 xl:col-span-2">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex justify-between items-center">
                        <p class="px-1">{{ __('learning/learningTestResult.custom.question_details') }}</p>

                        <div class="flex">
                            <div class="ml-2 px-2 py-1 text-black border border-gray-200 rounded-md flex opacity-70">
                                <x-tabler-award class="text-gray-950 mr-1" />
                                <span class="">
                                    @if ($this->view_test)
                                        @if ($this->getUserAnswer($this->position))
                                            {{ $this->getUserAnswer($this->position)?->points }} /
                                        @else
                                            0 /
                                        @endif
                                    @endif

                                    {{ $this->current_question->points }}
                                    {{ __('learning/learningTestResult.custom.points') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-slot>

                <form wire:submit.prevent="submitForm">
                    @if ($this->current_question->visual_content)
                        <div class="flex justify-center mb-5">
                            <a href="{{ asset($this->current_question->visual_content) }}" target="_blank">
                                <img src="{{ asset($this->current_question->visual_content) }}" alt="Visual content"
                                    class="rounded-md" style="max-width: 100%; width: auto; max-height: 400px;">
                            </a>
                        </div>
                    @endif
                    <div class="mb-5">
                        <h3 class="mb-1 text-lg font-bold">{{ $this->current_question->question_title }}</h3>
                        <p>{!! $this->current_question->question_description !!}</p>
                    </div>

                    @if ($this->current_question->answer_type != 'text')
                        <h3 class="mb-2 text-lg font-bold text-gray-900">
                            {{ __('learning/learningTestResult.custom.select_answer') }}
                        </h3>
                        <div class="mb-8">
                            @foreach ($this->getAnswers() as $index => $answer)
                                @if ($this->view_test)
                                    @if ($answer->is_correct || ($this->user_answer == $answer->answer && $answer->is_correct))
                                        <div class="mb-2 border-2 rounded-lg border-teal-500">
                                            <label class="relative rounded-lg p-5 flex bg-white">
                                                <input type="radio" wire:model="user_answer"
                                                    value="{{ $answer->answer }}" class="sr-only">
                                                <span class="flex-shrink-0 me-4">
                                                    <span
                                                        class="flex h-11 w-11 items-center justify-center rounded-full text-white font-bold bg-green-600 dark:bg-green-700">
                                                        <p class="text-xl text-white">
                                                            {{ $index + 1 }}
                                                        </p>
                                                    </span>
                                                </span>
                                                <span class="flex items-center">
                                                    <span class="flex flex-col text-lg">
                                                        <span
                                                            class="font-medium text-gray-900">{{ $answer->answer }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @elseif ($this->user_answer == $answer->answer && !$answer->is_correct)
                                        <div class="mb-2 border-2 rounded-lg border-rose-500">
                                            <label class="relative rounded-lg p-5 flex bg-white">
                                                <input type="radio" wire:model="user_answer"
                                                    value="{{ $answer->answer }}" class="sr-only">
                                                <span class="flex-shrink-0 me-4">
                                                    <span
                                                        class="flex h-11 w-11 items-center justify-center rounded-full text-white font-bold bg-red-600 dark:bg-rose-600">
                                                        <p class="text-xl text-white">
                                                            {{ $index + 1 }}
                                                        </p>
                                                    </span>
                                                </span>
                                                <span class="flex items-center">
                                                    <span class="flex flex-col text-lg">
                                                        <span
                                                            class="font-medium text-gray-900 dark:text-gray-100">{{ $answer->answer }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @else
                                        <div class="mb-2 border-2 border-gray-500 dark:border-gray-600 rounded-lg">
                                            <label class="relative rounded-lg p-5 flex bg-white dark:bg-gray-800">
                                                <input type="radio" wire:model="user_answer"
                                                    value="{{ $answer->answer }}" class="sr-only">
                                                <span class="flex-shrink-0 me-4">
                                                    <span
                                                        class="flex h-11 w-11 items-center justify-center rounded-full text-white font-bold bg-gray-500 dark:bg-gray-700">
                                                        <p class="text-xl text-white">
                                                            {{ $index + 1 }}
                                                        </p>
                                                    </span>
                                                </span>
                                                <span class="flex items-center">
                                                    <span class="flex flex-col text-lg">
                                                        <span
                                                            class="font-medium text-gray-900 dark:text-gray-100">{{ $answer->answer }}</span>
                                                    </span>
                                                </span>
                                            </label>
                                        </div>
                                    @endif
                                @else
                                    <div
                                        class="mb-2 border-2 hover:border-blue-500 dark:hover:border-blue-400 {{ $this->user_answer == $answer->answer ? 'border-blue-500 dark:border-blue-400' : '' }} rounded-lg">
                                        <label
                                            class="relative cursor-pointer rounded-lg p-5 flex bg-white dark:bg-gray-800">
                                            <input type="radio" wire:model="user_answer"
                                                value="{{ $answer->answer }}" class="sr-only">
                                            <span class="flex-shrink-0 me-4">
                                                <span
                                                    class="flex h-11 w-11 items-center justify-center rounded-full text-white font-bold {{ $this->user_answer == $answer->answer ? 'bg-blue-500 dark:bg-blue-400' : 'bg-gray-500 dark:bg-gray-700' }}">
                                                    <p class="text-xl text-white">
                                                        {{ $index + 1 }}
                                                    </p>
                                                </span>
                                            </span>
                                            <span class="flex items-center">
                                                <span class="flex flex-col text-lg">
                                                    <span
                                                        class="font-medium text-gray-900 dark:text-gray-100">{{ $answer->answer }}</span>
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="mb-8">
                            @if ($this->view_test)
                                @if ($this->getUserAnswer($this->current_question->id))
                                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ __('learning/learningTestResult.custom.your_answer') }}
                                    </h3>
                                    <input type="text" wire:model="user_answer"
                                        class="mb-5 border-2 rounded-lg w-full 
                                        {{ $this->isAnswerCorrect(
                                            $this->getUserAnswer($this->current_question->id)->user_answer,
                                            $this->getCorrectAnswer($this->current_question->id),
                                        )
                                            ? 'border-green-600 dark:border-green-600'
                                            : 'border-red-600 dark:border-red-600' }}
                                        bg-white text-black dark:bg-gray-800 dark:text-gray-200"
                                        disabled>
                                @else
                                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ __('learning/learningTestResult.custom.your_answer') }}
                                    </h3>
                                    <input type="text" wire:model="user_answer"
                                        class="mb-5 border-2 rounded-lg w-full border-red-600 dark:border-red-600 bg-white text-black dark:bg-gray-800 dark:text-gray-200"
                                        disabled>
                                @endif

                                @if (is_null($this->getUserAnswer($this->current_question->id)) ||
                                        !$this->isAnswerCorrect(
                                            $this->getUserAnswer($this->current_question->id)->user_answer,
                                            $this->getCorrectAnswer($this->current_question->id)))
                                    <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-gray-100">
                                        {{ __('learning/learningTestResult.custom.correct_answer') }}
                                    </h3>
                                    <input type="text"
                                        value="{{ $this->getCorrectAnswer($this->current_question->id) }}"
                                        class="border-2 rounded-lg w-full border-green-600 dark:border-green-600 bg-white text-black dark:bg-gray-800 dark:text-gray-200"
                                        disabled>
                                @endif
                            @else
                                <h3 class="mb-2 text-lg font-bold text-gray-900 dark:text-gray-100">
                                    {{ __('learning/learningTestResult.custom.enter_answer') }}
                                </h3>
                                <input type="text" wire:model="user_answer"
                                    class="border-2 rounded-lg w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            @endif
                        </div>
                    @endif

                    @if (
                        !(
                            $this->record->details->where('is_active', true)->count() - 1 == $this->result->details->count() &&
                            $this->view_test
                        ) && !($this->view_test && $this->record->details->where('is_active', true)->count() - 1 == $this->position))
                        <div class="text-center">
                            <x-filament::button type="submit" size="xl">
                                <div class="flex text-center p-1">
                                    @if ($this->view_test)
                                        <x-tabler-player-play class="text-white dark:text-gray-200 me-1 h-7 w-7" />
                                    @else
                                        <x-tabler-checkbox class="text-white dark:text-gray-200 me-1 h-7 w-7" />
                                    @endif
                                    <p class="text-lg p-0 m-0 text-white dark:text-gray-200">
                                        @if ($this->view_test && $this->record->details->where('is_active', true)->count() - 1 != $this->position)
                                            {{ __('learning/learningTestResult.custom.next') }}
                                        @elseif ($this->record->details->where('is_active', true)->count() - 1 == $this->result->details->count())
                                            {{ __('learning/learningTestResult.custom.finish') }}
                                        @elseif ($this->record->details->where('is_active', true)->count() - 1 > $this->result->details->count())
                                            {{ __('learning/learningTestResult.custom.submit') }}
                                        @endif
                                    </p>
                                </div>
                            </x-filament::button>
                        </div>
                    @endif
                </form>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>

@if (!$this->view_test)
    @script
        <script>
            document.addEventListener('livewire:initialized', function() {

                // send time
                const startTime = new Date();
                const rigaTime = new Intl.DateTimeFormat("en-US", {
                    timeZone: "Europe/Riga",
                    hour: "2-digit",
                    minute: "2-digit",
                    second: "2-digit",
                    year: "numeric",
                    month: "2-digit",
                    day: "2-digit",
                }).format(startTime);

                $wire.dispatch("start-time", {
                    startTime: rigaTime,
                });

                // set time limit
                let timeLeftElement = document.getElementById('time-left');

                if (timeLeftElement != null) {
                    let timeLeft = parseInt(timeLeftElement.textContent);

                    function formatTime(seconds) {
                        const hours = Math.floor(seconds / 3600);
                        const minutes = Math.floor((seconds % 3600) / 60);
                        const remainingSeconds = seconds % 60;

                        if (hours > 0) {
                            return `${hours}:${minutes < 10 ? '0' : ''}${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
                        } else {
                            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
                        }
                    }

                    function updateTimeLeft() {
                        if (timeLeft > 0) {
                            timeLeft--;
                            document.getElementById('time-left').textContent = formatTime(timeLeft);
                        } else {
                            clearInterval(timerInterval);
                            $wire.dispatch("end-time", {
                                endTime: timeLeft,
                            });
                        }
                    }

                    document.getElementById('time-left').textContent = formatTime(timeLeft);
                    const timerInterval = setInterval(updateTimeLeft, 1000);
                }

                // set active option
                const options = document.querySelectorAll('input[type="radio"]');
                options.forEach(option => {
                    option.addEventListener('click', () => {
                        const changeColor = option.parentElement.children[1].children[0];

                        // Remove the 'border-blue-500' class from all options
                        options.forEach(option => {
                            option.parentElement.parentElement.classList.remove(
                                'border-blue-500');
                            const sibling = option.parentElement.children[1].children[0];
                            sibling.classList.remove('bg-blue-500');
                            sibling.classList.add('bg-gray-500');
                        });

                        option.parentElement.parentElement.classList.add('border-blue-500');
                        changeColor.classList.remove('bg-gray-500');
                        changeColor.classList.add('bg-blue-500');
                    });
                });
            });
        </script>
    @endscript
@endif
