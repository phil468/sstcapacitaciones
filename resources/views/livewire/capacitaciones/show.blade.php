<div>
    <div class="card rounded-xl">
        <div class="text-white card-header bg-vanguard rounded-t-xl">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div class="float-left">
                    <h5 class="h5">Detalles de la Capacitación</h5>
                </div>
                <div>
                    <a href="{{ route('capacitaciones') }}" class="btn btn-default rounded-xl" title="Volver a lista de Capacitaciones">
                        <i class="fa fa-arrow-left"></i> 
                    </a>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <p><strong>Tema:</strong> {{ $capacitacion->tema->name }}</p>
            <p><strong>Sesiones:</strong> {{ $capacitacion->sesiones->count() }}</p>
            <p><strong>Activo:</strong> {{ $capacitacion->activo ? 'Si' : 'No' }}</p>
            <p><strong>Onboarding:</strong> {{ $capacitacion->es_onboarding ? 'Si' : 'No' }}</p>
            <p><strong>Fecha de Creación:</strong> {{ $capacitacion->created_at }}</p>
            <p><strong>Fecha de Actualización:</strong> {{ $capacitacion->updated_at }}</p>
        </div>
    </div>
</div>