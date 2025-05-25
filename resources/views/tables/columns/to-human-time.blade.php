<div class="ms-3">
    @if ($getState())
            <span class="text-sm">
                {{ gmdate('H:i:s', $getState()) }}
            </span>
    @else
        <span class="text-sm">
            {{ __('learning/learningResource.n_a') }}
        </span>
    @endif
</div>
