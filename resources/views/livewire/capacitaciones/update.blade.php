<!-- Modal -->
<div wire:ignore.self class="modal fade" id="updateModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
       <div class="rounded-2xl modal-content">
            <div class="text-white modal-header bg-vanguard rounded-t-2xl">
                <h5 class="modal-title h5" id="updateModalLabel">
                    @if ($this->selected_id == 0)
                        Nueva Capacitación
                    @else
                        Actualizar Capacitación
                    @endif
                </h5>
                <button type="button" class="text-white close" data-dismiss="modal" aria-label="Close">
                    <span wire:click.prevent="cancel()" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="row">
                        <div @if ($this->cargando) style="display: none;" @else style="display: block;" @endif
                            class="col-12 alert alert-warning" role="alert">
                            Cargando ...
                        </div>

                        <input type="hidden" wire:model="selected_id">
                    
                        <div class="form-group col-sm-6 col-md-6 col-lg-3 col-xl-2">
                            <label for="activo">Activo*</label>
                            <label class="mb-4 checkbox-container">
                                <input wire:model="activo" type="checkbox" class="custom-checkbox" id="activo" style="display: none;">
                                <span class="checkmark"></span>
                            </label>
                            @error('activo') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>

                                <div class="form-group col-sm-6 col-md-6 col-lg-3 col-xl-2"
                                    >
                                    @can('ingresar-capacitaciones-de-aula-virtual')
                                        @can('ingresar-capacitaciones-de-no-aula-virtual')   
                                            <label for="es_aula_virtual">Es Aula Virtual*</label>
                                            <label class="mb-4 checkbox-container">
                                                <input wire:model="es_aula_virtual" type="checkbox" class="custom-checkbox" id="es_aula_virtual" style="display: none;">
                                                <span class="checkmark"></span>
                                            </label>
                                            @error('es_aula_virtual') <span class="error text-danger">{{ $message }}</span> @enderror
                                        @endcan
                                    @endcan
                                </div>
                                
                                <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                    <label for="status_id">Estado *</label>
                                    <div wire:ignore>
                                    <select 
                                        name="status_id"
                                        class="form-control" 
                                        id="status_id" 
                                        placeholder="Estado">
                                    </select>
                                    </div>
                                    @error('status_id')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="identificador_unico">Identificador *</label>
                            <input wire:model.defer="identificador_unico" type="text" class="form-control" id="identificador_unico" placeholder="Identificador">@error('identificador_unico') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                                
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="empresa_id">Empresa *</label>
                            <div wire:ignore>
                            <select 
                                name="empresa_id"
                                class="form-control" 
                                id="empresa_id" 
                                placeholder="Empresas">
                            </select>
                            </div>
                            @error('empresa_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
                        @if ($es_aula_virtual) style="display: none;" @endif
                        >
                            <label for="sede_id">Sede *</label>
                            <div wire:ignore>
                            <select 
                                name="sede_id"
                                class="form-control" 
                                id="sede_id" 
                                placeholder="Sede">
                            </select>
                            </div>
                            @error('sede_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="modalidad_id">Modalidad *</label>
                            <div wire:ignore>
                            <select 
                                name="modalidad_id"
                                class="form-control" 
                                id="modalidad_id" 
                                placeholder="Modalidades">
                            </select>
                            </div>
                            @error('modalidad_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="capacitaciones_tipo_id">Tipo de Capacitacion*</label>
                            <div wire:ignore>
                            <select 
                                name="capacitaciones_tipo_id"
                                class="form-control" 
                                id="capacitaciones_tipo_id" 
                                placeholder="Tipo de capacitaciones">
                            </select>
                            </div>
                            @error('capacitaciones_tipo_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="tema_id">Tema *</label>
                            <div wire:ignore>
                            <select 
                                name="tema_id"
                                class="form-control" 
                                id="tema_id" 
                                placeholder="Tema">
                            </select>
                            </div>
                            <div class="input-group">
                                <input type="text" name="tema_id_add" wire:model.defer="tema_id_add"
                                    wire:keydown.enter='agregar_tema' wire:keydown.tab="agregar_tema"
                                    wire:keydown.arrow-right="agregar_tema" id="tema_id_add" class="form-control"
                                    placeholder="Ingresar TEMA y dar ENTER para agregar">
                                <button wire:click="agregar_tema" type="button" class="btn btn-vanguard"
                                    id="agregar_tema"><i class="fas fa-plus"></i></button>
                            </div>
                            @error('tema_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>                    

                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="fecha_inicio">Fecha Inicio*</label>
                            <input wire:model="fecha_inicio" type="datetime-local" class="form-control" id="fecha_inicio" placeholder="Fecha Inicio">@error('fecha_inicio') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="fecha_fin">Fecha Fin*</label>
                            <input wire:model="fecha_fin" type="datetime-local" class="form-control" id="fecha_fin" placeholder="Fecha Fin">@error('fecha_fin') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
            
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
                        @if ($es_aula_virtual) style="display: none;" @endif
                        >
                            <label for="expositor_externo">Expositor Interno / Externo *</label>
                            <div class="form-check">
                                Interno 
                                <label class="switch">
                                    <input type="checkbox" wire:model="expositor_externo" id="expositor_externo" name="expositor_externo" value="1">
                                    <span class="slider round"></span>
                                </label>
                                Externo
                                {{-- @if ($expositor_externo)
                                Externo
                                @else
                                Interno
                                @endif --}}
                                @error('activo')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
                        @if ($expositor_externo || $es_aula_virtual) style="display: none;" @endif
                        >
                            <label for="expositor_id">Expositor *</label>
                            <div wire:ignore>
                            <select 
                                name="expositor_id"
                                class="form-control" 
                                id="expositor_id" 
                                placeholder="Expositor">
                            </select>
                            </div>
                            
                            @error('expositor_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
                        @if ($expositor_externo || $es_aula_virtual) style="display: none;" @endif
                        >
                            <label for="cargo_expositor_id">Cargo de Expositor *</label>
                            <div wire:ignore>
                            <select 
                                name="cargo_expositor_id"
                                class="form-control" 
                                id="cargo_expositor_id" 
                                placeholder="Cargo de Expositor">
                            </select>
                            </div>
                            @error('cargo_expositor_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                                    
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
                        @if (!$expositor_externo || $es_aula_virtual) style="display: none;" @endif
                        >
                            <label for="nombre_expositor_externo">Nombre Expositor Externo</label>
                            <input 
                            @if (!$expositor_externo)
                                disabled
                            @endif
                            wire:model.defer="nombre_expositor_externo" class="form-control" id="nombre_expositor_externo" placeholder="Nombre Expositor Externo">
                            @error('nombre_expositor_externo') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div>
           
                        <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4"
                        @if ($es_aula_virtual) style="display: none;" @endif
                        >
                            <label for="area_id">Área</label>
                            <div wire:ignore>
                                <select 
                                    name="area_id"
                                    class="form-control" 
                                    id="area_id"
                                    placeholder="Area"
                                    multiple
                                    >
                                </select>
                            </div>
                            @error('area_id')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
            
                        {{-- <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                            <label for="cantidad_de_sesiones">Cantidad de sesiones</label>
                            <input wire:model.defer="cantidad_de_sesiones" min="1" type="number" class="form-control" id="cantidad_de_sesiones" placeholder="Cantidad de Sesiones">@error('cantidad_de_sesiones') <span class="error text-danger">{{ $message }}</span> @enderror
                        </div> --}}

                        @can('ingresar-capacitaciones-de-aula-virtual')
                            <div   
                            @if ($es_aula_virtual) class="col-12" @else class="d-none" @endif >
                                <br>
                                <div class="mt-2 h5">
                                    Opciones de Aula Virtual:
                                </div>
                                <hr>

                                <div class="mt-2 row">
                                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                        <label for="es_onboarding">Es Onboarding*</label>
                                        <label class="mb-4 checkbox-container">
                                            <input wire:model="es_onboarding" type="checkbox" class="custom-checkbox" id="es_onboarding" style="display: none;">
                                            <span class="checkmark"></span>
                                        </label>
                                        @error('es_onboarding') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                        <label for="cantidad_de_preguntas_a_mostrar">Cantidad de preguntas a mostrar*</label>
                                        <input wire:model.defer="cantidad_de_preguntas_a_mostrar" type="number" class="form-control" id="cantidad_de_preguntas_a_mostrar" placeholder="Cantidad de preguntas a mostrar">
                                        <small id="cantidad_de_preguntas_a_mostrar_help" class="form-text text-muted">Por defecto: {{$cantidad_de_preguntas_a_mostrar_general}}</small>
                                        @error('cantidad_de_preguntas_a_mostrar') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                        <label for="nota_minima_aprobatoria">Nota mínima aprobatoria*</label>
                                        <input wire:model.defer="nota_minima_aprobatoria" type="number" class="form-control" id="nota_minima_aprobatoria" placeholder="Nota mínima aprobatoria">
                                        <small id="nota_minima_aprobatoria_help" class="form-text text-muted">Por defecto: {{$nota_minima_aprobatoria_general}}</small>
                                        @error('nota_minima_aprobatoria') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="form-group col-sm-12 col-md-12 col-lg-6 col-xl-4">
                                        <label for="intentos_de_evaluacion">Intentos de evaluación*</label>
                                        <input wire:model.defer="intentos_de_evaluacion" type="number" class="form-control" id="intentos_de_evaluacion" placeholder="Intentos de evaluación">
                                        <small id="intentos_de_evaluacion_help" class="form-text text-muted">Por defecto: {{$intentos_de_evaluacion_general}}</small>
                                        @error('intentos_de_evaluacion') <span class="error text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" wire:click.prevent="cancel()" class="btn btn-vanguard"
                    data-dismiss="modal">Cerrar</button>

                @if ($this->selected_id == 0)
                    <button type="button" wire:click.prevent="store()"
                        class="btn btn-vanguard close-modal btn-lg">Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-vanguard btn-lg"
                        @if (!$this->updateMode) disabled @endif>Guardar</button>
                @endif
            </div>
       </div>
    </div>

    <style>

        .checkbox-container {
            display: block;
            position: relative;
            padding-left: 35px;
            cursor: pointer;
            font-size: 22px;
            user-select: none;
        }

        .checkbox-container input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 40px;
            width: 40px;
            background-color: #eee;
            border-radius: 50%;
            border: 5px solid #568ba5; /* Agrega un borde */

        }

        .checkbox-container:hover input ~ .checkmark {
            background-color: #ccc;
        }

        .checkbox-container input:checked ~ .checkmark {
            background-color: white;
        }

        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        .checkbox-container input:checked ~ .checkmark:after {
            display: block;
        }

        .checkbox-container .checkmark:after {
            left: 10.2px;
            top: 4px;
            width: 10px;
            height: 20px;
            border: solid #568ba5;
            border-width: 0 5px 5px 0;
            transform: rotate(45deg);
        }
    </style>
    
</div>
