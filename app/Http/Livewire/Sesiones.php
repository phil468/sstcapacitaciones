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
    public $selected_id, $keyWord, $capacitacion_id, $numero_de_sesion, $fecha, $hora_inicio, $hora_fin, $urlVideo, $capacitacion_id_general, $video, $name, $videoUrl;
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
        // dd(Sesione::orderBy('numero_de_sesion')
        // ->when($this->capacitacion_id_general, function ($query) {
        //     return $query->where('capacitacion_id', $this->capacitacion_id_general);
        // })->get()->toArray());
		// $keyWord = '%'.$this->keyWord .'%';
        return view('livewire.sesiones.view', [
            'sesiones' => Sesione::orderBy('numero_de_sesion')
                        ->when($this->capacitacion_id_general, function ($query) {
                            return $query->where('capacitacion_id', $this->capacitacion_id_general);
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
        $this->videoUrl = null;
    }

    public function store()
    {
        // dd($this->video);
        $this->validate([
		    'capacitacion_id' => 'required',
        ]);
        
        // Manejar la carga del archivo
        if ($this->video) {
            $videoPath = $this->video->store('video_sesiones');
            // $video = $videoPath;
        }

        Sesione::create([ 
			'capacitacion_id' => $this-> capacitacion_id,
			'numero_de_sesion' => $this-> numero_de_sesion,
			'fecha' => $this-> fecha,
			'hora_inicio' => $this-> hora_inicio,
			'hora_fin' => $this-> hora_fin,
            'video' => $videoPath??null,
            'name' => $this-> name
        ]);
        
        $this->resetInput();
		$this->emit('closeModal');
		session()->flash('message', 'Sesión creada correctamente.');
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
            // $this->video = $record-> video;
            $this->videoUrl = $record->video;
            $this->name = $record-> name;
        } else {
            $this->video = null;
            $this->selected_id = 0;
        }
		
        // dd($this->video);
        $this->updateMode = true;
    }

    public function update($selected_id)
    {
        if ($selected_id == 0 || $selected_id == null) 
        {            
            $this->validate([
                'capacitacion_id' => 'required',
                'video' => 'required|mimes:mp4,mov,ogg,qt,video/mp4|max:61440',
                'numero_de_sesion' => 'required',
                'name' => 'required',
            ], [
                'video.required' => 'El campo video es obligatorio.',
                'video.mimes' => 'El video debe ser un archivo de tipo: mp4, mov, ogg, qt, video/mp4.',
                'video.max' => 'El video no debe ser mayor a 60MB.',
                'numero_de_sesion.required' => 'El campo número de sesión es obligatorio.',
                'name.required' => 'El campo nombre es obligatorio.',
            ] , [
                'video' => 'video',
                'numero_de_sesion' => 'número de sesión',
                'name' => 'nombre',
            ]);
            
            // Manejar la carga del archivo
            if ($this->video) {
                $videoPath = $this->video->store(null,'video_sesiones');
            }

            Sesione::create([ 
                'capacitacion_id' => $this-> capacitacion_id,
                'numero_de_sesion' => $this-> numero_de_sesion,
                'fecha' => $this-> fecha,
                'hora_inicio' => $this-> hora_inicio,
                'hora_fin' => $this-> hora_fin,
                'video' => $videoPath??null,
                'name' => $this-> name
            ]);
            
            $this->resetInput();
            $this->emit('closeModal');
            session()->flash('message', 'Sesión creada correctamente.');
        } else {

            $this->validate([
                'capacitacion_id' => 'required',
                'numero_de_sesion' => 'required',
                'name' => 'required',
            ], [
                // 'video.required' => 'El campo video es obligatorio.',
                // 'video.mimes' => 'El video debe ser un archivo de tipo: mp4, mov, ogg, qt, video/mp4.',
                // 'video.max' => 'El video no debe ser mayor a 60MB.',
                'numero_de_sesion.required' => 'El campo número de sesión es obligatorio.',
                'name.required' => 'El campo nombre es obligatorio.',
            ] , [
                // 'video' => 'video',
                'numero_de_sesion' => 'número de sesión',
                'name' => 'nombre',
            ]
            );

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
                session()->flash('message', 'Sesión actualizada correctamente.');
            }
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
        $this->resetValidation();
        $this->resetInput();

		if ($id != 0) {
            $record = Sesione::findOrFail($id);
            $this->video = $record-> video;
        } else {
            $this->video = null;
        }

        $this->updateMode = false;
    }

    public function download($id)
    {
        $record = Sesione::find($id);
        //return Storage::download('video_sesiones')->url($record->video);
        // dd(Storage::url('adm/'.$record->video));
        return Storage::download('video_sesiones/'.$record->video);
    }

    public function resetVideoPreview()
    {
        $this->video = null;
        $this->videoUrl = null;
    }
}
