<div class="flex flex-col max-h-[700px] overflow-y-auto h-full">
    @if ($courses->count() > 0)
        @foreach ($courses as $course)
            <div class="grid grid-cols-12 border-b border-gray-200 dark:border-gray-700 py-2">
                <div class="col-span-4 md:col-span-4">
                    <a href="{{ route('filament.app.resources.learning-categories.course-welcome-page', ['record' => $course->id]) }}"
                        class="group">
                        <div class="flex items-center gap-3 pe-3 my-2">
                            @if (!$course->thumbnail)
                                <span class="relative inline-block">
                                    <div
                                        class="rounded-full overflow-hidden h-9 w-9 flex items-center justify-center group-hover:opacity-70 border border-gray-300">
                                        @svg('tabler-photo-off', 'text-gray-600 dark:text-gray-300 h-6 w-6')
                                    </div>
                                </span>
                            @else
                                <span class="relative inline-block">
                                    <div
                                        class="rounded-full overflow-hidden h-9 w-9 flex items-center justify-center group-hover:opacity-70">
                                        <img src="{{ asset($course->thumbnail) }}" class="w-full h-full object-cover">
                                    </div>
                                </span>
                            @endif
                            <div class="flex flex-col justify-center mb-1 w-full">
                                <p class="text-sm text-gray-900 dark:text-gray-100 group-hover:text-gray-500 mb-1">
                                    {{ Str::limit($course->name, 32) }}
                                </p>
                                <div class="flex flex-row items-center w-full">
                                    <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                        <div class="bg-blue-500 h-2.5 rounded-full group-hover:opacity-70"
                                            style="width: {{ $this->progresses[$course->id] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-span-8 md:col-span-8 flex flex-col justify-end h-full py-2 group-hover:opacity-70">
                    <div class="flex justify-between">
                        <p>
                            {{ $this->progresses[$course->id] }} / 100 %
                        </p>

                        <p>
                            @if ($course->learningResources->count() == 1)
                                {{ $course->learningResources->count() }} Resource
                            @else
                                {{ $course->learningResources->count() }} Resources
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="flex items-center justify-center h-full">
            <p class="text-gray-500 dark:text-gray-400">
                No courses found
            </p>
        </div>
    @endif

    <div class="mt-4 py-2">
        <x-filament::pagination :paginator="$courses" />
    </div>
</div>
