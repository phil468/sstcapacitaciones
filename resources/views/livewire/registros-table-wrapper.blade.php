<div>
    @if($faltanUsuarios > 0)
        <div class="alert alert-warning py-2 px-3 mb-2">
            <strong>{{ $faltanUsuarios }}</strong> {{ Str::plural('persona', $faltanUsuarios) }} activa{{ $faltanUsuarios>1?'s':'' }} sin usuario en el sistema.
            <button wire:click="recontar" class="btn btn-xs btn-outline-secondary ml-2">Recontar</button>
        </div>
    @else
        <div class="alert alert-success py-2 px-3 mb-2">
            Todos los personales activos tienen usuario.
        </div>
    @endif

    <livewire:registros-table :capacitacion_id="$capacitacion_id" :exportable="false" />
</div>