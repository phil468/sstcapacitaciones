@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
                    <h3 class="card-title">Editar Usuario</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {!! Form::model($user, ['method' => 'PATCH', 'route' => ['users.update', $user->id]]) !!}

                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre:</label>
                        {!! Form::text('name', $value = $user->name, ['class' => 'form-control']) !!}
                        @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        {!! Form::text('email', $value = $user->email, ['class' => 'form-control']) !!}
                        @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        {!! Form::text('password', '', ['class' => 'form-control']) !!}
                        @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="confirm-password" class="form-label">Confirmar password:</label>
                        {!! Form::text('confirm-password', old('confirm-password'), ['class' => 'form-control']) !!}
                        @error('confirm-password')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="personal_id" class="form-label">Personal: {{ $user->personal->name }}</label>
                        <select id="personal_id" name="personal_id" class="form-control select2-ajax" style="width: 100%;"
                            data-selected="{{ $user->personal_id }}"></select>

                        {{-- <div class="mb-3">
						<label>Registrador</label>
						<div class="form-check">
							{!! Form::checkbox('registrador', 1, $user->registrador ? true:false, array('class' => 'form-check-input')) !!}
							<label class="form-check-label" for="registrador">
								Sí
							</label>
						</div>
						@error('registrador') <span class="error text-danger">{{ $message }}</span> @enderror
					</div> --}}

                        <div class="mb-3">
                            <label for="roles" class="form-label">Roles:</label>
                            {!! Form::select('roles[]', $roles, $userRole, [
                                'class' => 'form-control',
                                'placeholder' => 'Seleccione uno o varios roles...',
                                'multiple',
                            ]) !!}
                            @error('roles')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label>Estado</label>
                            <div class="form-check">
                                {!! Form::checkbox('estado', 1, $user->estado ? true : false, ['class' => 'form-check-input']) !!}
                                <label class="form-check-label" for="estado">
                                    Activo
                                </label>
                            </div>
                            @error('estado')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <a class="mb-3 btn btn-default btn-sm" href="{{ route('users.index') }}">Regresar</a>
                            <button type="submit" class="mb-3 btn btn-vanguard">Editar Usuario</button>
                            {{-- <a class="mb-3 btn btn-success" href="{{route('users.index')}}">Regresar</a> --}}
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    @stop

    @section('css')
        <link rel="stylesheet" href="/css/admin_custom.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @stop

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                const selectedId = $('#personal_id').data('selected');

                $('#personal_id').select2({
                    placeholder: 'Buscar personal por nombre o DNI...',
                    ajax: {
                        url: '{{ route('api.personal.select2.personal') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.results
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1,
                    allowClear: true,
                    initSelection: function(element, callback) {
                        // Cargar el valor seleccionado si existe
                        if (selectedId) {
                            $.ajax({
                                url: '{{ route('api.personal.select2.personal') }}',
                                data: {
                                    q: ''
                                },
                                dataType: 'json',
                                success: function(data) {
                                    const selected = data.results.find(r => r.id == selectedId);
                                    if (selected) {
                                        callback(selected);
                                    }
                                }
                            });
                        }
                    }
                });

                // Si hay un valor seleccionado, establecerlo
                if (selectedId) {
                    $('#personal_id').val(selectedId).trigger('change');
                }
            });
        </script>
    @stop
