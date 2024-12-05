<div style="position: relative; width: 100%; height: 100%;">
    <video id="videoPlayer" 
    {{-- src="{{ $videoUrl }}"  --}}
    src="/sstcapacitaciones/public/storage/video_sesiones/eeWjZfyUdlVcldimEiayQ3YyiNVSXPV6ZnUACJGD.mp4" 
    controls @loadedmetadata="onLoadedMetadata" 
    {{-- style="position: absolute; width: {{ 100 * $cols }}%; height: {{ 100 * $rows }}%; top: -{{ 100 * floor(($part - 1) / $cols) }}%; left: -{{ 100 * (($part - 1) % $cols) }}%;" --}}
    >
</video>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('videoPlayer');
    const startTime = new Date(@json($startTime));
    const now = new Date();
    const delay = startTime - now;

    if (delay > 0) {
        setTimeout(() => {
            video.play();
        }, delay);
    } else {
        video.play();
    }
});
</script>