@if ($getLink())
    <a href="{{ $getLink() }}" class="group">
@endif
<div class="flex items-center gap-3 pe-3 my-2 ms-3">
    @if ($getAvatarType() == 'image')
        @if (is_null($getAvatar()))
            <span class="relative inline-block">
                <div
                    class="rounded-full overflow-hidden {{ $getBgSize() }} flex items-center justify-center group-hover:opacity-70 border border-gray-300">
                    @svg('tabler-photo-off', 'text-gray-600 dark:text-gray-300 {{ $getAvatarSize() }}')
                </div>
            </span>
        @else
            <span class="relative inline-block">
                <div
                    class="rounded-full overflow-hidden {{ $getBgSize() }} flex items-center justify-center group-hover:opacity-70">
                    <img src="{{ asset($getAvatar()) }}" class="w-full h-full object-cover">
                </div>
            </span>
        @endif
    @elseif ($getAvatarType() == 'name_xs')
        <span class="relative inline-block">
            <div class="rounded-full overflow-hidden {{ $getBgSize() }} flex items-center justify-center group-hover:opacity-70 border border-gray-300"
                style="background-color: {{ $getBgColor() ? $getBgColor() : '#377DFF' }};">
                <p class='{{ $getAvatarColor() ? $getAvatarColor() : 'text-white' }} {{ $getAvatarSize() }} font-bold'>{{ $getNameXs() }}</p>
            </div>
        </span>
    @elseif ($getAvatarType() == 'icon')
        <span class="relative inline-block">
            <div
                class="rounded-full overflow-hidden {{ $getBgSize() }} {{ $getAvatarColor() ? $getAvatarColor() : 'text-gray-600 dark:text-gray-300' }} flex items-center justify-center group-hover:opacity-70 border border-gray-300">
                @svg($getIcon(), $getAvatarSize())
            </div>
        </span>
    @endif
    <div class="flex flex-col justify-center mb-1">
        <p class="text-sm text-gray-900 dark:text-gray-100 group-hover:text-gray-500">
            {{ Str::limit($getTitle(), $getTitleLimit()) }}</p>
        <div class="flex flex-row items-center">
            @if ($getDescriptionIcon())
                <div
                    style="height: 18px; width: 19px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                    @svg($getDescriptionIcon(), 'text-gray-500 dark:text-gray-300 pe-1 group-hover:text-gray-400')
                </div>
            @endif
            <p class="text-xs text-gray-600 dark:text-gray-300 group-hover:text-gray-400 ">
                {{ Str::limit($getDescription(), $getDescriptionLimit()) }}</p>
        </div>
    </div>
</div>
@if ($getLink())
    </a>
@endif
