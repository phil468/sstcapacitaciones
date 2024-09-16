    <div class="btn-group">
        
        <a title="Editar" data-toggle="modal" data-target="#updateModal" class="btn btn-vanguard" wire:click="edit({{$id}})"><i class="fa fa-edit"></i></a>

        <a title="Ver" class="btn btn-warning" href="{{ route('capacitaciones.show', ['id' => $id]) }}"><i class="fa fa-eye"></i></a>

        <a title="Eliminar" class="btn btn-danger" onclick="confirm('¿Confirma eliminar {{$id}} ? \n ¡Los datos eliminados no pueden ser recuperados!')
        ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a>

    </div>
 