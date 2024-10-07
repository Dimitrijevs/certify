<div class="ms-4">
    @if (!is_null($getState()))
        @if($getState() == $getDefaultState())
            <span class="text-sm">
                {{ $getState() }}
            </span>
        @else
            <span class="text-sm">
                {{ gmdate('H:i:s', $getState()) }}
            </span>
        @endif

    @else
        <span class="text-sm">
            N/A
        </span>
    @endif
</div>
