<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Video;
use Carbon\Carbon;

class UploadVideo extends Component
{
    use WithFileUploads;

    public $title;
    public $video;
    public $start_time;
    public $rows = 1;
    public $cols = 1;

    protected $rules = [
        'title' => 'required|string|max:255',
        'video' => 'required|file|mimes:mp4,mov,ogg,qt|max:20000',
        // 'start_time' => 'required|date_format:Y-m-d H:i:s',
        'rows' => 'required|integer|min:1',
        'cols' => 'required|integer|min:1'
    ];

    public function upload()
    {
        $this->validate();

        $path = $this->video->store('videos');

        Video::create([
            'title' => $this->title,
            'path' => $path,
            'start_time' => Carbon::parse($this->start_time),
            'rows' => $this->rows,
            'cols' => $this->cols
        ]);

        session()->flash('message', 'Video subido exitosamente.');
    }

    public function render()
    {
        return view('livewire.upload-video');
    }
}