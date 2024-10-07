<div x-data x-init="window.addEventListener('beforeunload', () => {
    @this.call('recordLeave', {{ $record->id }});
});">
</div>

@script
    <script>
        $wire.on('video-start', (latestPoint) => {
            window.latestWatchedTime = latestPoint;
        });
    </script>
@endscript
