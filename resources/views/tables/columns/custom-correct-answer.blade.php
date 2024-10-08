<style>
    .ps {
        padding-left: 0.6vw;
    }
</style>

<div>
    <p class="ps text-sm">
        {{ \Illuminate\Support\Str::limit($this->getQuestionName($getState()), 12, '... ') }}
    </p>
</div>
