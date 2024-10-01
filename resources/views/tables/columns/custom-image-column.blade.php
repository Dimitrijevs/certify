<div class="w-full mb-2">
    @if (!is_null($getState()))
        <img src="{{ asset($getState()) }}" class="rounded-md" alt=""
            style="height: 200px; width: 100%; object-fit: cover;">
    @else
        <div class="aspect-w-16 aspect-h-9 w-full rounded-lg bg-gray-200 flex justify-center items-center"
            style="height: 200px">
            <span class="text-lg text-gray-600"><x-tabler-photo-off class="w-12 h-12" /></span>
        </div>
    @endif
</div>