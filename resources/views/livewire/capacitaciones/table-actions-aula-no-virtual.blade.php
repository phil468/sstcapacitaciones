    <div class="btn-group">
        <a title="Editar" data-toggle="modal" data-target="#updateModal" class="btn btn-outline-vanguard m-0.5 btn-lg" wire:click="edit({{$id}})"><i class="fa fa-edit"></i></a>

        <a title="Agregar Personal" 
            class="btn btn-outline-info m-0.5 btn-lg"
            href="{{route('capacitaciones.personal', $id)}}"
            >
            <i class="fas fa-user-plus"></i>
            {{-- <i class="fas fa-user-add"></i> --}}
        </a>

        <a title="Registrar Asistencia"
            class="btn btn-outline-success m-0.5 btn-lg"
            href="{{route('capacitaciones.asistencia', $id)}}"
            >
            <i class="fas fa-user-check"></i>
            {{-- <i class="fas fa-user-check"></i> --}}
        </a>

        <a title="Eliminar" class="btn btn-outline-danger m-0.5 btn-lg" onclick="confirm('¿Confirma eliminar {{$id}} ? \n ¡Los datos eliminados no pueden ser recuperados!')
        ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a>
       
           {{-- @if(isset($pdf))
                <a title="Descargar PDF" class="text-white btn btn-warning" wire:click="descargarPDF('{{$pdf}}')"><i class="fas fa-file-pdf fa-lg"></i></a>              
            @endif   --}}
    </div>
 