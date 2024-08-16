<?php

namespace App\Http\Livewire;

use App\Models\Capacitacione;
use Livewire\Component;
use Livewire\WithFileUploads; // Importa el trait
use Livewire\WithPagination;
use App\Models\Sesione;
use Illuminate\Support\Facades\Storage;

class Sesiones extends Component
{
    use WithPagination;
    use WithFileUploads; // Usa el trait

	protected $paginationTheme = 'bootstrap';
    public $selected_id, $keyWord, $capacitacion_id, $numero_de_sesion, $fecha, $hora_inicio, $hora_fin, $urlVideo, $capacitacion_id_general, $video, $name;
    public $updateMode = false;
    public $capacitacion;
    
    public function mount($capacitacion_id = null) {
        $this->capacitacion_id_general = $capacitacion_id ?? null;
        if ( $this->capacitacion_id_general) {
            $this->capacitacion = Capacitacione::find($capacitacion_id); //
        }
    }

    public function render()
    {
		$keyWord = '%'.$this->keyWord .'%';
        return view('livewire.sesiones.view', [
            'sesiones' => Sesione::latest()
						->orWhere('capacitacion_id', 'LIKE', $keyWord)
						->orWhere('numero_de_sesion', 'LIKE', $keyWord)
						->orWhere('fecha', 'LIKE', $keyWord)
						->orWhere('hora_inicio', 'LIKE', $keyWord)
						->orWhere('hora_fin', 'LIKE', $keyWord)
                        ->when($this->capacitacion_id, function ($query) {
                            return $query->where('capacitacion_id', $this->capacitacion_id);
                        })
						->paginate(10),
        ]);
    }
	
    public function cancel()
    {
        $this->resetInput();
        $this->updateMode = false;
    }
	
    private function resetInput()
    {		
		$this->capacitacion_id = $this->capacitacion_id_general ?? null;
		$this->numero_de_sesion = null;
		$this->fecha = null;
		$this->hora_inicio = null;
		$this->hora_fin = null;
        $this->video = null;
        $this->name = null;
    }

    public function store()
    {
        $this->validate([
		    'capacitacion_id' => 'required',
        ]);
        
        // Manejar la carga del archivo
        if ($this->video) {
            $videoPath = $this->video->store('video_sesiones', 'public');
            $video = $videoPath;
        }

        Sesione::create([ 
			'capacitacion_id' => $this-> capacitacion_id,
			'numero_de_sesion' => $this-> numero_de_sesion,
			'fecha' => $this-> fecha,
			'hora_inicio' => $this-> hora_inicio,
			'hora_fin' => $this-> hora_fin,
            'video' => $video??null,
            'name' => $this-> name
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Sesione creado correctamente.');
    }

    public function edit($id)
    {
        // dd(public_path('storage'));
        $this->resetValidation();
        $this->resetInput();
        
		if ($id != 0) {
            $record = Sesione::findOrFail($id);
            $this->selected_id = $id; 
            $this->capacitacion_id = $record-> capacitacion_id;
            $this->numero_de_sesion = $record-> numero_de_sesion;
            $this->fecha = $record-> fecha;
            $this->hora_inicio = $record-> hora_inicio;
            $this->hora_fin = $record-> hora_fin;
            $this->video = $record-> video;
            $this->name = $record-> name;
        }
		
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'capacitacion_id' => 'required',
            // 'video' => 'required|mimes:mp4,mov,ogg,qt|max:20000', // 20MB
        ]);

        // Manejar la carga del archivo
        if ($this->video) {
            $videoPath = $this->video->store(null,'video_sesiones');
            $video = $videoPath;
        }

        if ($this->selected_id) {
			$record = Sesione::find($this->selected_id);
            $record->update([ 
                'capacitacion_id' => $this-> capacitacion_id,
                'numero_de_sesion' => $this-> numero_de_sesion,
                'fecha' => $this-> fecha,
                'hora_inicio' => $this-> hora_inicio,
                'hora_fin' => $this-> hora_fin,
                'video' => $videoPath ?? $record->video, // Mantener el video existente si no se carga uno nuevo
                'name' => $this-> name
            ]);

            $this->resetInput();
            $this->updateMode = false;
		    $this->emit('closeModal');
			session()->flash('message', 'Sesione actualizado correctamente.');
        }
    }

    public function destroy($id)
    {
        if ($id) {
            $record = Sesione::where('id', $id);
            $record->delete();
        }
    }

    public function showVideo($id)
    {
        $record = Sesione::find($id);
        if ($record && $record->video) {
            // subir a la carpeta de los assets

            $url = Storage::disk('video_sesiones')->url($record->video);
            $this->urlVideo = $url;
            // dd($url);
        } else {
            // Manejar el caso en que no se encuentra el registro o el video
            $this->urlVideo = null;
        }
    }

    public function download($id)
    {
        $record = Sesione::find($id);
        // dd(Storage::url('adm/'.$record->video));
        return Storage::download('adm/'.$record->video);
    }
}
