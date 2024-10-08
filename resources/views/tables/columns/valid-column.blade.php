<div class="flex justify-between w-full">
    <p class="flex-grow text-start">{{ $getRecord()->created_at->format('Y-m-d') }}</p>
    <span class="mx-2">-</span>
    <p class="flex-grow text-end">{{ $getRecord()->valid_to }}</p>
</div>
