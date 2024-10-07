<style>
    #player iframe {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    @media (max-width: 768px) {
        .sm-text-remove {
            display: none;
        }
    }
</style>

<x-filament-panels::page>
    <x-filament::breadcrumbs :breadcrumbs="[
        '/' => __('learning/learningCategory.custom.home'),
        '/learning-categories' => __('learning/learningCategory.label_plural'),
        '/learning-categories/' . $this->record->category_id . '/edit' => $this->record->category->name,
        '/learning-categories/resource/' . $this->record->id => $this->record->name,
    ]" />

    <div class="border-b border-t border-gray-200">
        <nav class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" aria-label="Progress">
            <ol role="list"
                class="overflow-hidden rounded-md flex lg:rounded-none border-l border-r border-gray-200 w-full">
                <li class="relative overflow-hidden flex-1">
                    @if (is_null($this->getPreviousResource()))
                        <div
                            class="overflow-hidden rounded-t-md border border-b-0 border-gray-200 border-0 opacity-50 flex justify-center lg:justify-start items-center h-full">
                            <span class="flex items-center px-6 py-5 text-sm font-medium h-full">
                                <span class="flex-shrink-0">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                                        <x-tabler-note-off class="text-white" />
                                    </span>
                                </span>
                                <span class="ml-4 my-auto flex min-w-0 flex-col text-center sm-text-remove">
                                    <span
                                        class="text-md font-medium">{{ __('learning/learningResource.custom.no_previous_resources') }}</span>
                                </span>
                            </span>
                        </div>
                    @else
                        <a href="{{ route('filament.app.resources.learning-categories.resource', ['record' => $this->getPreviousResource()->id]) }}"
                            class="group h-full">
                            <div
                                class="overflow-hidden rounded-b-md border border-t-0 border-gray-200 border-0 relative flex justify-center lg:justify-start items-center h-full">
                                <span
                                    class="absolute bg-transparent group-hover:bg-gray-200 bottom-0 top-auto h-1 w-full"
                                    aria-hidden="true"></span>
                                <span class="flex items-center justify-center px-6 py-5 text-sm font-medium h-full">
                                    <span class="flex-shrink-0">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                                            <x-tabler-arrow-narrow-left class="text-white" />
                                        </span>
                                    </span>
                                    <span class="ml-4 my-auto flex min-w-0 flex-col text-center sm-text-remove">
                                        <span class="text-md">{{ $this->getPreviousResource()->name }}</span>
                                    </span>
                                </span>
                            </div>
                        </a>
                    @endif
                    <!-- Separator -->
                    <div class="absolute inset-y-0 right-0 w-3 block" aria-hidden="true">
                        <svg class="h-full w-full text-gray-300" viewBox="0 0 12 82" fill="none"
                            preserveAspectRatio="none">
                            <path d="M11.5 0V31L1.5 41L11.5 51V82" stroke="currentcolor"
                                vector-effect="non-scaling-stroke" />
                        </svg>
                    </div>
                </li>

                <li class="relative overflow-hidden flex-1">
                    <div class="overflow-hidden border-0 flex justify-center items-center">
                        <span class="absolute bg-blue-500 bottom-0 top-auto h-1 w-full" aria-hidden="true"></span>
                        <span
                            class="flex flex-col sm:flex-row justify-center items-center px-6 py-5 text-sm font-medium">
                            <span class="flex-shrink-0">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-blue-500 bg-blue-500">
                                    <x-tabler-book class="text-white" />
                                </span>
                            </span>
                            <span class="sm:ml-4 my-auto flex min-w-0 flex-col text-center">
                                <span class="text-md font-medium text-blue-500 mt-1 sm:mt-0">{{ $record->name }}</span>
                            </span>
                        </span>
                    </div>
                </li>

                <li class="relative overflow-hidden flex-1">
                    @if (is_null($this->getNextResource()))
                        <div
                            class="overflow-hidden rounded-t-md border-0 opacity-50 flex justify-center lg:justify-end items-center h-full">
                            <span class="flex items-center px-6 py-5 text-sm font-medium">
                                <span class="mr-4 my-auto flex min-w-0 flex-col text-center sm-text-remove">
                                    <span
                                        class="text-md">{{ __('learning/learningResource.custom.no_next_resources') }}</span>
                                </span>
                                <span class="flex-shrink-0">
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                                        <x-tabler-note-off class="text-white" />
                                    </span>
                                </span>
                            </span>
                        </div>
                    @else
                        <a href="{{ route('filament.app.resources.learning-categories.resource', ['record' => $this->getNextResource()->id]) }}"
                            class="group w-full">
                            <div
                                class="overflow-hidden rounded-b-md border-0 flex justify-center lg:justify-end items-center h-full">
                                <span
                                    class="absolute bg-transparent group-hover:bg-gray-200 bottom-0 top-auto h-1 w-full"
                                    aria-hidden="true"></span>
                                <span class="flex items-center justify-center px-6 py-5 text-sm font-medium">
                                    <span class="mr-4 my-auto flex min-w-0 flex-col text-center sm-text-remove">
                                        <span class="text-md">{{ $this->getNextResource()->name }}</span>
                                    </span>
                                    <span class="flex-shrink-0">
                                        <span
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500">
                                            <x-tabler-arrow-narrow-right class="text-white" />
                                        </span>
                                    </span>
                                </span>
                            </div>
                        </a>
                    @endif
                    <!-- Separator -->
                    <div class="absolute inset-0 left-0 top-0 w-3 block" aria-hidden="true">
                        <svg class="h-full w-full text-gray-300" viewBox="0 0 12 82" fill="none"
                            preserveAspectRatio="none">
                            <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentcolor"
                                vector-effect="non-scaling-stroke" />
                        </svg>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="grid lg:grid-cols-12 md:grid-cols-12 gap-4">
        <div class="lg:col-span-4 md:col-span-4 order-2 lg:order-1 md:order-1 ">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="m-0 p-0 flex justify-between items-center">
                        {{ $this->getParent()->name }}

                        @if (auth()->user()->can('update_learning::category'))
                            <a
                                href="{{ route('filament.app.resources.learning-categories.edit', ['record' => $this->getParent()->id]) }}">
                                <div
                                    title="{{ __('learning/learningCategory.form.edit') }} {{ $this->getParent()->name }}">
                                    <x-filament::button color="gray">
                                        <x-tabler-edit class="h-4 w-4 text-gray-700 dark:text-white" />
                                    </x-filament::button>
                                </div>
                            </a>
                        @endif
                    </div>
                </x-slot>
                @if ($this->getParent()->thumbnail)
                    <img src="{{ asset($this->getParent()->thumbnail) }}" class="rounded-md" alt=""
                        style="height: 200px; width: 100%; object-fit: cover;">
                @else
                    <div class="aspect-w-16 aspect-h-9 w-full rounded-lg bg-gray-200 flex justify-center items-center"
                        style="height: 180px">
                        <span class="text-lg text-gray-600"><x-tabler-photo-off class="w-12 h-12" /></span>
                    </div>
                @endif

                <div class="my-4">{!! Str::limit($this->getParent()->description, 200) !!}</div>

                <h3
                    class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white mb-2">
                    {{ __('learning/learningCategory.custom.overall_progress') }}
                </h3>
                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700 mt-2">
                    <div class="bg-blue-500 h-2.5 rounded-full" style="width: {{ $this->getProgress() }}%;"></div>
                </div>
            </x-filament::section>

            <x-filament::section class="mt-4">
                <x-slot name="heading">
                    {{ __('learning/learningResource.label_plural') }}
                </x-slot>

                <ul class="space-y-4">
                    @foreach ($this->getUserActivity() as $resource)
                        <li class="group">
                            <a
                                href="{{ route('filament.app.resources.learning-categories.resource', ['record' => $resource->id]) }}">
                                <span class="flex items-start">
                                    <span class="relative flex h-6 w-6 flex-shrink-0 items-center justify-center">
                                        @if ($resource->is_current)
                                            <span
                                                class="absolute h-5 w-5 rounded-full bg-blue-200 dark:bg-blue-700"></span>
                                            <span
                                                class="relative block h-3 w-3 rounded-full bg-blue-500 dark:bg-blue-300"></span>
                                        @elseif ($resource->is_seen)
                                            <svg class="h-full w-full text-blue-600 dark:text-blue-300 group-hover:text-blue-400 dark:group-hover:text-blue-500"
                                                viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        @else
                                            <div
                                                class="h-3 w-3 rounded-full bg-gray-400 dark:bg-gray-600 group-hover:bg-gray-300 dark:group-hover:bg-gray-500">
                                            </div>
                                        @endif
                                    </span>
                                    @if ($resource->is_current)
                                        <span
                                            class="ml-4 text-md text-blue-600 dark:text-blue-300 group-hover:text-blue-400 dark:group-hover:text-blue-500">{{ $resource->name }}</span>
                                    @else
                                        <span
                                            class="ml-4 text-md font-medium text-gray-500 dark:text-gray-300 group-hover:text-blue-400 dark:group-hover:text-blue-500">{{ $resource->name }}</span>
                                    @endif
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                @livewire('user-activity-form', ['record' => $record->id])
            </x-filament::section>
        </div>

        <div class="lg:col-span-8 md:col-span-8 order-1 lg:order-2 md:order-2">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="m-0 p-0 flex justify-between items-center">
                        {{ $record->name }}

                        @if (auth()->user()->can('update_learning::category'))
                            <a
                                href="{{ route('filament.app.resources.learning-categories.editResource', ['record' => $record->id]) }}">
                                <div title="{{ __('learning/learningCategory.form.edit') }} {{ $record->name }}">
                                    <x-filament::button color="gray">
                                        <x-tabler-edit class="h-4 w-4 text-gray-700 dark:text-white" />
                                    </x-filament::button>
                                </div>
                            </a>
                        @endif
                    </div>
                </x-slot>

                <div class="flex flex-col justify-center items-center">
                    <div class="w-full lg:max-w-6xl">
                        <input type="hidden" value="{{ $record->video_type }}" name="video_type" id="video_type">
                        <input type="hidden" value="{{ $record->video_url }}" name="video_url" id="video_url">

                        @if (filter_var($record->video_url, FILTER_VALIDATE_URL))
                            <div id="player" class="aspect-w-16 aspect-h-9 w-full rounded-lg overflow-hidden mb-5">
                                <!-- YouTube/Vimeo player embed code goes here -->
                            </div>
                        @endif
                    </div>
                </div>

                @if ($record->gallery)
                    <div class="overflow-hidden mb-5">
                        @include('components.slider', ['gallery' => $record->gallery])
                    </div>
                @endif

                <div class="w-full lg:max-w-6xl">
                    @if (!empty($record->file_upload) && is_array($record->file_upload))
                        <div class="mb-5 w-full">
                            <h3
                                class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white mb-2">
                                Learning materials
                            </h3>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-400">
                                <div>
                                    @foreach ($record->file_upload as $file)
                                        <p class="hover:underline">
                                            <a href="{{ asset($file) }}" download
                                                class="">{{ basename($file) }}</a>
                                        </p>
                                    @endforeach
                                </div>
                            </dd>
                        </div>
                    @endif
                    <h3
                        class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white mb-2">
                        {{ $record->name }}
                    </h3>
                    <div>{!! $record->description !!}</div>
                </div>
            </x-filament::section>
        </div>

    </div>
</x-filament-panels::page>

<script src="/js/videoPlayer/youtube_iframe_api.js"></script>
<script src="/js/videoPlayer/vimeo_player.js"></script>

@script
    <script>
        document.addEventListener("livewire:initialized", function() {
            let totalTimeWatched = 0;
            let videoDuration = 0;
            let latestWatchedTime = 0;
            let lastTimeUpdate = 0;
            let screenWidth = window.innerWidth;
            let player;

            if (window.latestWatchedTime !== undefined) {
                videoWatchPoint = window.latestWatchedTime.latestPoint;
            } else {
                videoWatchPoint = 0;
            }

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

            function setVideoSource(src, type) {
                const playerContainer = document.getElementById("player");
                if (playerContainer !== null) {
                    playerContainer.innerHTML = "";
                }

                if (type === "video/youtube" && src.includes("watch?v=")) {
                    window.YT.ready(function() {
                        loadYouTubePlayer(getYouTubeVideoId(src), videoWatchPoint);
                    });
                } else if (
                    type === "video/vimeo" &&
                    !src.includes("player.vimeo.com")
                ) {
                    loadVimeoPlayer(getVimeoVideoId(src), videoWatchPoint);
                } else {
                    // not working yet, for test purposes,
                    // use only YouTube and Vimeo,
                    // if video upload will be available in the future, then we will use this
                    // loadHTML5Player(src, type);
                }
            }

            function getYouTubeVideoId(url) {
                return url.split("watch?v=")[1].split("&")[0];
            }

            function getVimeoVideoId(url) {
                const regex = /vimeo\.com\/(?:.*\/)?(\d+)/;
                const match = url.match(regex);
                return match ? match[1] : null;
            }

            function loadHTML5Player(src, type) {
                const playerContainer = document.getElementById("player");
                const videoPlayer = document.createElement("video");
                videoPlayer.controls = true;
                videoPlayer.width = "100%";
                videoPlayer.innerHTML = `<source src="${src}" type="${type}">`;
                playerContainer.appendChild(videoPlayer);

                videoPlayer.addEventListener(
                    "play",
                    () => (lastTimeUpdate = Date.now())
                );
                videoPlayer.addEventListener("pause", () =>
                    updateTimeWatched(videoPlayer.currentTime)
                );
                videoPlayer.addEventListener("loadedmetadata", () => {
                    videoDuration = videoPlayer.duration;
                    console.log("Video duration:", videoDuration, "seconds");
                });
                videoPlayer.addEventListener("timeupdate", () => {
                    latestWatchedTime = videoPlayer.currentTime;
                    console.log("Latest watched time:", latestWatchedTime);
                    updateTimeWatched(videoPlayer.currentTime);
                });

                player = videoPlayer;
            }

            function loadYouTubePlayer(videoId, videoWatchPoint = 0) {
                player = new YT.Player("player", {
                    videoId: videoId,
                    playerVars: {
                        start: videoWatchPoint,
                    },
                    events: {
                        onReady: onPlayerReady,
                        onStateChange: onPlayerStateChange,
                    },
                });
            }

            function loadVimeoPlayer(videoId, videoWatchPoint = 0) {
                if (screenWidth < 400) {
                    player = new Vimeo.Player("player", {
                        id: videoId,
                        width: 280,
                    });
                } else if (screenWidth < 640) {
                    player = new Vimeo.Player("player", {
                        id: videoId,
                        width: 320,
                    });
                } else if (screenWidth < 768) {
                    player = new Vimeo.Player("player", {
                        id: videoId,
                        width: 520,
                    });
                } else if (screenWidth < 1024) {
                    player = new Vimeo.Player("player", {
                        id: videoId,
                        width: 460,
                    });
                } else if (screenWidth < 1280) {
                    player = new Vimeo.Player("player", {
                        id: videoId,
                        width: 580,
                    });
                } else {
                    player = new Vimeo.Player("player", {
                        id: videoId,
                        width: 660,
                    });
                }

                player.setCurrentTime(videoWatchPoint);

                player.getDuration().then((duration) => {
                    videoDuration = duration;
                    $wire.dispatch("update-video-duration", {
                        videoDuration: videoDuration,
                    });
                });

                player.on("timeupdate", (data) => {
                    if (data.seconds > latestWatchedTime) {
                        latestWatchedTime = data.seconds;
                        $wire.dispatch("update-video-progress", {
                            latestWatchedTime: latestWatchedTime,
                        });
                    }
                    updateTimeWatched(data.seconds);
                });

                player.on("play", () => (lastTimeUpdate = Date.now()));
            }

            function onPlayerReady(event) {
                lastTimeUpdate = Date.now();
                videoDuration = player.getDuration();
                $wire.dispatch("update-video-duration", {
                    videoDuration: videoDuration,
                });

                setInterval(() => {
                    if (player.getPlayerState() === YT.PlayerState.PLAYING) {
                        if (player.getCurrentTime() > latestWatchedTime) {
                            latestWatchedTime = player.getCurrentTime();
                            $wire.dispatch("update-video-progress", {
                                latestWatchedTime: latestWatchedTime,
                            });
                        }
                        updateTimeWatched(player.getCurrentTime());
                    }
                }, 800);
            }

            function onPlayerStateChange(event) {
                if (event.data === YT.PlayerState.PLAYING) {
                    lastTimeUpdate = Date.now();
                }
            }

            function updateTimeWatched(currentTime) {
                const now = Date.now();
                totalTimeWatched += (now - lastTimeUpdate) / 1000;
                $wire.dispatch("update-video-watched", {
                    timeWatched: totalTimeWatched,
                });
                lastTimeUpdate = now;
            }

            const videoURL = document.getElementById("video_url").value;
            const type = document.getElementById("video_type").value;
            setVideoSource(videoURL, type);
        });
    </script>
@endscript
