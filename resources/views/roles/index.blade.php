@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <div class="float-left">
                        <h3 class="h4">Roles</h3>
                    </div>
                    @can('crear-rol')
                        <a class="float-right mt-1 mr-1 btn btn-default rounded-xl" href="{{ route('roles.create') }}">
                            <i class="fa fa-plus"></i>
                        </a>                        
                    @endcan
                </div>
            <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mt-2 table-striped table-hover">
                            <thead>
                                <th>Acciones</th>
                                <th>Rol</th>
                                <th>Permisos</th>
                            </thead>  
                            <tbody>
                            @foreach ($roles as $role)
                            <tr>
                                <td>
                                    <div class="btn-group">
                                        @can('editar-rol')
                                            <a class="m-1 btn btn-outline-vanguard" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-edit"></i></a>
                                        @endcan
                                        
                                        @can('borrar-rol')
                                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                            <button title="Eliminar" class="m-1 btn btn-outline-danger" type="submit" 
                                            value="borrar" style="width: 43.8px;"><i class="fa fa-trash"></i></button>
                                            {!! Form::close() !!}
                                        @endcan
                                    </div>
                                </td>
                                <td>{{ $role->name }}</td>                    
                                <td>
                                    @if(!empty($role->getPermissionNames()))
                                    @foreach($role->getPermissionNames() as $name)
                                        <span class="border-rounded badge badge-primary">{{ $name }}</span>
                                    @endforeach
                                    @endif
                                </td>  
                            </tr>
                            @endforeach
                            </tbody>               
                        </table>  
                    </div>  
                </div>
            <!-- /.card-body -->
            
                <div class="pagination justify-content-end">
                    {!! $roles->links() !!} 
                </div>        

            </div>
            <!-- /.card -->
        </div>
    </div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop