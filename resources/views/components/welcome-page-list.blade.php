<div class="space-y-4">
    @if ($title)
        <p class="font-bold">{{ $title }}</p>
    @endif

    @if ($description)
        <ul class="space-y-4">
            {{ $description }}
        </ul>
    @endif
</div>
