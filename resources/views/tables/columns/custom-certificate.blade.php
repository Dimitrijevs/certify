<div class="w-full mb-2">
    <div class="aspect-w-16 aspect-h-9 w-full rounded-lg flex flex-col justify-center items-center"
        style="height: 360px; background-color: rgb(250,223,21);">
        <p class="font-bold text-base mt-2 text-black">{{ $getRecord()->user->name }}</p>
        <span class="text-lg text-gray-600 my-auto">
            <x-tabler-trophy class="w-16 h-16 text-black" />
        </span>
        <p class="font-bold text-base mb-2 text-black">{{ $getRecord()->created_at->format('Y') }}</p>
    </div>
</div>
