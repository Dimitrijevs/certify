<div class="w-full mb-2 relative">
    @if (!is_null($getState()))
        <img src="{{ asset($getState()) }}" class="rounded-md" alt=""
            style="height: 200px; width: 100%; object-fit: cover;">
    @else
        <div class="aspect-w-16 aspect-h-9 w-full rounded-lg bg-gray-200 flex justify-center items-center"
            style="height: 200px">
            <span class="text-lg text-gray-600"><x-tabler-photo-off class="w-12 h-12" /></span>
        </div>
    @endif

    @if ($getLanguageName())
        <div
            class="absolute bg-blue-50 border border-blue-600 rounded-xl shadow-md -top-2 -right-2 px-2 py-1 text-blue-800">
            <p>{{ $getLanguageName() }}</p>
        </div>
    @endif

    @if ($getCategories())
        <div
            class="absolute bg-blue-50 border border-blue-600 rounded-xl shadow-md -bottom-2 -right-2 px-2 py-1 text-blue-800">
            <p>{{ Str::limit($getCategories(), 24) }}</p>
        </div>
    @endif
</div>
