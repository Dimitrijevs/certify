<div class="ms-3">
    @if ($getState())
            <span class="text-sm">
                {{ gmdate('H:i:s', $getState()) }}
            </span>
    @else
        <span class="text-sm">
            N/A
        </span>
    @endif
</div>
