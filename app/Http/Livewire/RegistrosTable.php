<?php

namespace App\Http\Livewire;

use App\Models\Alerta;
use App\Models\AlertaEnviada;
use App\Models\CapacitacionHasPersonal;
use App\Models\NotificacionesEnviada;
use App\Models\Personal;
use App\Models\Prueba;
use App\Models\Respuesta;
use App\Models\SesionAccessLog;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CapacitacionNotification;

class RegistrosTable extends LivewireDatatable
{
    public $capacitacion_id, $selected_personal_id, $sede_id, $gerencia_id, $subgerencia_id, $area_id;
    public $hideable = 'inline';
    public $exportable = true;
    public $beforeTableSlot = 'components.edicionMasiva';
    // public $updateMode = false;
    public $selected = [];
    // public $showColumns = ['id', 'dni']; // Columnas predeterminadas
    public $listaParaAgregar = false;
    public $modalEdicionMasiva = '#updateRegistroModal';
        
    protected $listeners = [
        'closeModal' => '$refresh',
        'refrescarRegistroTable' => '$refresh',
        'limpiarSeleccionRegistroTable'=>'limpiarSeleccionRegistroTable'
    ];    
    
    public function limpiarSeleccionRegistroTable()
    {
        $this->selected = [];
    }

    public function edicionMasiva()
    {
        $this->emitUp('edicionMasiva', $this->selected);
    }

    public function builder()
    {
        return CapacitacionHasPersonal::query()
            ->with(['personal.user']) // <-- eager load para evitar N+1
            ->where('capacitacion_id', $this->capacitacion_id);
    }

    public function columns()
    {
        return [
            Column::checkbox()
            ->label('Add'),

            Column::callback('id,personal.name', function ($id,$name) {
                return view('livewire.capacitacion-has-personals.table-actions', ['id' => $id, 'name'=>$name]);
            })->unsortable()
            ->label('Acciones')
            ->excludeFromExport(),
            
            // Column::name('id')->label('ID'),
            Column::name('personal.dni')->label('DNI')->sortable()->searchable(),
            Column::name('personal.name')
            ->label('Nombre del Personal')
            ->sortable()
            ->searchable()
            ->filterable()
            ->defaultSort('ASC'),

            Column::name('personal.correo_empresa')
            ->label('Correo Empresa')
            ->sortable()
            ->searchable()
            ->filterable(),


            // NUEVA COLUMNA: Usuario
            Column::callback('personal_id', function ($personal_id) {
                $p = \App\Models\Personal::with('user')->find($personal_id);
                if(!$p) return '';
                if($p->user){
                    return $p->user->email;
                }
                return '<button type="button" class="btn btn-sm btn-outline-primary crear-usuario-btn" data-personal="'.$p->id.'">Crear Usuario</button>';
            },[],'crear_usuario' )
            ->label('Usuario')
            ->excludeFromExport(),

            // columna que muestra si tiene o no tiene usuario
            Column::callback('personal_id', function ($personal_id) {
                $p = \App\Models\Personal::with('user')->find($personal_id);
                if(!$p) return '';
                if($p->user){
                    return '<span class="badge badge-success">Sí</span>';
                }
                return '<span class="badge badge-danger">No</span>';
            },[],'usuario')
            ->label('Tiene Usuario')
            ->filterable([
                // 1 => 'Sí',
                0 => 'No',
            ], 'conUsuario')
            ->excludeFromExport(),

            // NUEVA COLUMNA: Advertencias
            // Columna Advertencias
            Column::callback('personal_id', function ($personal_id) {
                $p = \App\Models\Personal::with('user')->select('id','correo_empresa')->find($personal_id);
                if(!$p) return '';
                if(!$p->user){
                    return '<span class="text-warning" title="Sin usuario"><i class="fas fa-exclamation-triangle"></i> Sin usuario</span>';
                }
                if(!$p->correo_empresa){
                    return '<span class="text-warning" title="Sin correo_empresa"><i class="fas fa-envelope-open-text"></i> Sin correo</span>';
                }
                if($p->correo_empresa && $p->user->email && strcasecmp($p->correo_empresa,$p->user->email)!==0){
                    return '<span class="text-danger" title="Correo y usuario difieren"><i class="fas fa-exclamation-circle"></i> Correo y usuario difieren</span>';
                }
                return '';
            },[],'Advertencia')->label('Advert.')
         
            ->excludeFromExport(),

            // columna de cesados
            Column::callback('personal.cesado', function ($cesado) {
                $cesado = (int) ($cesado ?? 0); // trata null como 0 (Activo)
                if ($cesado === 1) {
                    return '<span class="badge badge-danger">Cesado</span>';
                }
                return '<span class="badge badge-success">Activo</span>';
            }, [], 'personal.cesado')
            ->label('Estado')
            // ->filterable([
            //     0 => 'Activo',
            //     1 => 'Cesado'
            // ])
            ->excludeFromExport(),
            
            DateColumn::name('fecha_inicio')->format('d/m/Y h:i:s a')
            ->label('Fecha de inicio (Aula Virtual)')->searchable()->filterable()->sortable(),

            DateColumn::name('fecha_fin')->format('d/m/Y h:i:s a')
            ->label('Fecha de fin (Aula Virtual)')->searchable()->filterable()->sortable(),

            NumberColumn::callback('id', function ($id) {
                return CapacitacionHasPersonal::find($id)->intentos_de_evaluacion;
            })->label('Intentos de Evaluación')->sortable()->searchable(),

            Column::name('empresa.name')->label('Empresa')->sortable()->searchable(),
            Column::name('gerencia.name')->label('Gerencia')->sortable()->searchable(),
            Column::name('sede.name')->label('Sede')->sortable()->searchable(),
            // Column::name('subgerencia.name')->label('Subgerencia')->sortable()->searchable(),
            Column::name('area.name')->label('Área')->sortable()->searchable(),
            Column::name('cargo.name')->label('Cargo')->sortable()->searchable(),
            Column::name('planilla.name')->label('Planilla')->sortable()->searchable(),
            Column::name('tipo_de_trabajador.name')->label('Tipo de Trabajador')->sortable()->searchable(),
            Column::name('tipo_de_personal.name')->label('Tipo de Personal')->sortable()->searchable(),
            // Column::name('capacitacion_has_personal.name')->label('Capacitación')->sortable()->searchable(),
            // ->hide(),
            // Column::name('capacitacion')->label('Nombre de la Capacitación'),
            // Agrega más columnas según tus necesidades
            // Column::callback(['id'], function ($id) {
            //     return view('livewire.capacitacion-has-personal-table-actions', ['id' => $id]);
            // })->label('Acciones')->unsortable()->excludeFromExport(),
        ];
    }

    public function edit($id)
    {
        // dd($id);
        $this->emitUp('edit', $id);
    }

    public function destroy($id)
    {
        if ($id) {
            $record = CapacitacionHasPersonal::find($id);

            SesionAccessLog::where('personal_id', $record->personal_id)
            ->where('capacitacion_id', $record->capacitacion_id)
            ->delete();

            // eliminar pruebas
            $pruebas = Prueba::where('personal_id', $record->personal_id)
            ->where('capacitacion_id', $record->capacitacion_id);

            foreach ($pruebas as $prueba) {
                // Eliminar respuestas asociadas a la prueba
                Respuesta::where('prueba_id', $prueba->id)->delete();
            }
            
            // Eliminar las pruebas
            Prueba::where('personal_id', $record->personal_id)
                ->where('capacitacion_id', $record->capacitacion_id)
                ->delete();

            AlertaEnviada::where('capacitacion_has_personal_id', $id)
                ->delete();

            NotificacionesEnviada::where('capacitacion_id', $record->capacitacion_id)
                ->where('personal_id', $record->personal_id)
                ->delete();
            
            $record->delete();
            
            $this->emit('alert', ['type' => 'success', 'message' => 'Registro eliminado con éxito.']);
        }
    }

    public function notificarIndividual($id)
    {
        $registro = CapacitacionHasPersonal::with([
            'personal.user',
            'capacitacion.estado'
        ])->find($id);

        if (!$registro) {
            $this->emit('alert', ['type'=>'danger','message'=>'Registro no encontrado.']);
            return;
        }

        $capacitacion = $registro->capacitacion;

        if (!$capacitacion || !$capacitacion->activo || ($capacitacion->estado && strtolower($capacitacion->estado->name) === 'cancelado')) {
            $this->emit('alert', ['type'=>'warning','message'=>'Capacitación inactiva o cancelada.']);
            return;
        }

        // Validar ventana (si existen columnas fecha_inicio / fecha_fin en la tabla pivote)
        if ($registro->fecha_inicio && $registro->fecha_fin) {
            $now = now();
            if (!($registro->fecha_inicio <= $now && $registro->fecha_fin >= $now)) {
                $this->emit('alert', ['type'=>'info','message'=>'Fuera del rango de fechas para notificar.']);
                return;
            }
        }

        // Verificar si ya realizó evaluación (mismo criterio que notificar general)
        $yaIngreso = SesionAccessLog::where('capacitacion_id', $capacitacion->id)
            ->where('personal_id', $registro->personal_id)
            ->whereNotNull('numero_de_evaluacion')
            ->exists();

        if ($yaIngreso) {
            $this->emit('alert', ['type'=>'info','message'=>'El personal ya registró actividad (no se envía).']);
            return;
        }

        if (!$registro->personal || !$registro->personal->user) {
            $this->emit('alert', ['type'=>'danger','message'=>'El personal no tiene usuario asociado.']);
            return;
        }

        try {
            Notification::send($registro->personal->user, new CapacitacionNotification($capacitacion));

            NotificacionesEnviada::create([
                'capacitacion_id' => $capacitacion->id,
                'personal_id'     => $registro->personal_id
            ]);

            $this->emit('alert', [
                'type'=>'success',
                'message'=>'Notificación enviada a '.$registro->personal->name
            ]);
        } catch (\Throwable $e) {
            $this->emit('alert', [
                'type'=>'danger',
                'message'=>'Error al enviar notificación.'
            ]);
        }
    }

}
