<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Video;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class VideoPlayer extends Component
{
    public $videoId;
    public $part;
    public $rows;
    public $cols;
    public $videoUrl;
    public $startTime;

    public function mount($videoId, $part)
    {
        $this->videoId = $videoId;
        $this->part = $part;

        $video = Video::findOrFail($this->videoId);
        $this->videoUrl = Storage::url($video->path);
        $this->startTime = new Carbon($video->start_time);
        $this->rows = $video->rows;
        $this->cols = $video->cols;
    }

    public function render()
    {
        return view('livewire.video-player');
    }
}