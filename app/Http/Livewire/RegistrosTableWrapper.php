<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\CapacitacionHasPersonal;

class RegistrosTableWrapper extends Component
{
    public $capacitacion_id;
    public $faltanUsuarios = 0;

    protected $listeners = [
        'refrescarRegistroTable' => 'recontar' // se emite cuando se crea usuario
    ];

    public function mount($capacitacion_id)
    {
        $this->capacitacion_id = $capacitacion_id;
        $this->recontar();
    }

    public function recontar()
    {
        $this->faltanUsuarios = CapacitacionHasPersonal::query()
            ->where('capacitacion_id', $this->capacitacion_id)
            ->whereHas('personal', function ($q) {
                $q->where(function($q){
                    $q->whereNull('cesado')->orWhere('cesado', 0);
                })->whereDoesntHave('user');
            })
            ->count();
    }

    public function render()
    {
        return view('livewire.registros-table-wrapper');
    }
}