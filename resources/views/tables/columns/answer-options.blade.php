<style>
    .ps {
        padding-left: 0.6vw;
    }
</style>

<div class="ps">
    @if (is_array($getState()))
        @foreach ($getState() as $item)
            <span class="text-sm">
                {{ \Illuminate\Support\Str::limit($item, 8, '... ') }}
            </span>
        @endforeach
    @else
        <span class="text-sm">
            N/A
        </span>
    @endif
</div>
