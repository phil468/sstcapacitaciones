    <div class="btn-group">
        
        <a title="Editar" data-toggle="modal" data-target="#updateModal" class="btn btn-outline-vanguard m-0.5 btn-lg" wire:click="edit({{$id}})"><i class="fa fa-edit"></i></a>

        <a title="Ver" class="btn btn-outline-warning m-0.5 btn-lg" href="{{ route('capacitaciones.show', ['id' => $id]) }}"><i class="fa fa-eye"></i></a>

        <a title="Eliminar" class="btn btn-outline-danger m-0.5 btn-lg" onclick="confirm('¿Confirma eliminar {{$id}} ? \n ¡Los datos eliminados no pueden ser recuperados!')
        ||event.stopImmediatePropagation()" wire:click="destroy({{$id}})"><i class="fa fa-trash"></i></a>

    </div>
 