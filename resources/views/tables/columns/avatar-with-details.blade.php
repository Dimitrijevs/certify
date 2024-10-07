@if ($getLink())
    <a href="{{ $getLink() }}" class="group">
@endif
<div class="flex items-center {{ $getMarginStart() }} gap-3 pe-3">
    @if ($getAvatarType() == 'image')
        @if (is_null($getAvatar()))
            <div
                class="rounded-full overflow-hidden {{ $getBgSize() }} flex items-center justify-center group-hover:opacity-80 border border-gray-200 dark:border-gray-700">
                @svg('tabler-user', 'text-gray-400 dark:text-gray-500 h-6 w-6')
            </div>
        @elseif (strlen($getAvatar()) < 3)
            <div
                class="rounded-full overflow-hidden {{ $getBgSize() }} flex items-center justify-center group-hover:opacity-80 border">
                <p class='text-black dark:text-white text-base'>{{ $getAvatar() }}</p>
            </div>
        @else
            <div
                class="rounded-full overflow-hidden {{ $getBgSize() }} flex items-center justify-center group-hover:opacity-80">
                <img src="{{ asset($getAvatar()) }}" class="w-full h-full object-cover">
            </div>
        @endif
    @elseif ($getAvatarType() == 'name_xs')
        <div class="rounded-full {{ $getBgSize() }} flex items-center justify-center group-hover:opacity-80"
            style="background-color: {{ $getBgColor() }};">
            <p class='text-white text-base font-bold'>{{ $getNameXs() }}</p>
        </div>
    @elseif ($getAvatarType() == 'icon')
        <div class="flex items-center justify-center group-hover:opacity-80 {{ $getBgSize() }}">
            @svg($getIcon(), 'text-gray-700 dark:text-gray-400 h-full w-full')
        </div>
    @endif
    <div class="flex flex-col justify-center mb-1">
        <p class="text-sm text-gray-900 dark:text-gray-100 group-hover:text-gray-500 dark:group-hover:text-gray-400">
            {{ $getTitle() }}</p>
        <p class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-gray-400 dark:group-hover:text-gray-500">
            {{ $getDescription() }}</p>
    </div>
</div>
@if ($getLink())
    </a>
@endif
