<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CapacitacionImportController;
use App\Http\Controllers\PersonalImportController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneraReporte;
use App\Http\Controllers\PersonalController;
use App\Models\Asignacione;
use App\Models\EvaluadorHasEvaluado;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Livewire;
use GuzzleHttp\Client;
use App\Http\Livewire\UploadVideo;
use App\Http\Livewire\VideoPlayer;
use Carbon\Carbon;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('mis-capacitaciones');
    // view('dash.index');
});

//Ruta HOME:
// Route::get('/connect', [App\Http\Controllers\HomeController::class,'redirectToAzure']);

Route::get('/auth/redirect', function () {
    return Socialite::driver('azure')
    // ->scopes([
    //     'api://e5a37484-1e31-499f-94af-fd254c7422d4/Contacts.Read',
    //     'api://e5a37484-1e31-499f-94af-fd254c7422d4/User.ReadBasic.All'
    //     ]) // Solicita el ámbito específico
    ->redirect('/mis-capacitaciones');
});
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('azure')->user();

    $localUser = User::where('email', $user->email)->first();

    //he agregado el atributo employeeNumber

    // si el usuario no existe en la base de datos local, lo creamos con rol 'PERSONAL', clave autogenerada y personal_id nulo
    if (!$localUser) {
        // El usuario no existe, crea un nuevo usuario
        $localUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            // puedes agregar más campos aquí si los necesitas
        ]);
    }

    // Inicia sesión con el usuario
    Auth::login($localUser, true);
    $loginController = new LoginController();
    $loginController->auditlogin($localUser);

    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/me/contacts');
    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/users');
    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/users');


    // dd($response->json());

    // Redirige al usuario a la página de inicio o a donde quieras
    return redirect('/home');
    //return redirect(route('dash.index'));

});

Route::get('/logout', function () {
    Auth::guard()->logout();
    return redirect('/login');
        
    // $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login')); // reemplaza con tu URL de redirección
    // return redirect()->away($azureLogoutUrl);
    // $request->session()->flush();
    // $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
    // return redirect($azureLogoutUrl);
})->name('logout');

// Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dash.index');
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dash.index');
Route::get('/personal/importar/{numero}', [App\Http\Controllers\PersonalController::class,'actualizarPersonalNisira'])->name('personal.actualizar');
Route::get('/personal/actualizarEstadoParaTodos', [App\Http\Controllers\PersonalController::class,'actualizarEstadoParaTodos'])->name('personal.actualizarEstadoParaTodos');
Route::get('/planilla/importar/{empresa}/{val}', [App\Http\Controllers\PlanillaController::class,'upsert'])->name('planilla.upsert');
Route::get('/tipodepersonal/importar/{empresa}/{val}', [App\Http\Controllers\TipoDePersonalController::class,'upsert'])->name('tipodepersonal.upsert');
Route::get('/tipodetrabajador/importar/{empresa}/{val}', [App\Http\Controllers\TipoDeTrabajadorController::class,'upsert'])->name('tipodetrabajador.upsert');
Route::get('/procesar-excel', [App\Http\Controllers\PersonalController::class, 'procesarExcel'])->name('procesar.excel');

Route::get('/report/audit-logins', [App\Http\Controllers\ReportController::class, 'auditLogins'])->name('report.audit_logins');
Route::get('/report/specific-tables', [App\Http\Controllers\ReportController::class, 'specificTables'])->name('report.specific_tables');

//Rutas de autenticación
Auth::routes();

// Route::get('/upload', UploadVideo::class);
Route::view('/upload','livewire.index_upload')->name('index_upload');
Route::view('/video/{videoId}/{part}','livewire.index_video')->name('index_video');

// Route::get('/video/{videoId}/{part}', VideoPlayer::class)->name('video');

Route::group(['middleware'  =>  ['auth']],function(){

    Route::get('import-capacitaciones', [CapacitacionImportController::class, 'showImportForm'])->name('capacitaciones.import.form')->middleware(['can:ver-import-capacitaciones']);
    Route::post('import-capacitaciones', [CapacitacionImportController::class, 'import'])->name('capacitaciones.import')->middleware(['can:ver-import-capacitaciones']);
    Route::post('confirm-import-capacitaciones', [CapacitacionImportController::class, 'confirmImport'])->name('capacitaciones.confirm-import')->middleware(['can:ver-import-capacitaciones']);

    Route::get('capacitaciones/importar-personal', [PersonalImportController::class, 'showImportForm'])->name('capacitaciones.personal.import.form')->middleware(['can:ver-import-capacitaciones']);
    Route::post('capacitaciones/importar-personal', [PersonalImportController::class, 'import'])->name('capacitaciones.personal.import')->middleware(['can:ver-import-capacitaciones']);
    Route::post('capacitaciones/confirmar-importacion-personal', [PersonalImportController::class, 'confirmImport'])->name('capacitaciones.personal.confirm-import')->middleware(['can:ver-import-capacitaciones']);
    Route::get('capacitaciones/resultado-importacion-personal', [PersonalImportController::class, 'showResultImport'])->name('capacitaciones.personal.result-import')->middleware(['can:ver-import-capacitaciones']);

    Route::get('/download/{id}', [App\Http\Controllers\EvidenciaController::class,'download'])->name('download');

    Route::resource('roles',RolController::class);

    Route::view('/home','home')->name('welcome');
    //Prueba domPDF
    // Route::get('/users/{user_id}/dompdf',[UserController::class,'dompdf'])->name('users.dompdf');
    Route::view('/dashboard','livewire.dashboard.index')->name('dashboard')->middleware(['can:ver-dashboard']);
    Route::view('/configuracion-general','livewire.configuracion-general.index')->name('configuracion-general')->middleware(['can:ver-configuracion-general']);
    Route::view('/alertas','livewire.alertas.index')->name('alertas')->middleware(['can:ver-alertas']);
    Route::view('/alertas-enviadas','livewire.alerta-enviadas.index')->name('alertas-enviadas')->middleware(['can:ver-alertas']);
    Route::view('/notificaciones-enviadas','livewire.notificaciones-enviadas.index')->name('notificaciones-enviadas')->middleware(['can:ver-alertas']);
    Route::view('/personal','livewire.personals.index')->name('personal')->middleware(['can:ver-personal']);    
    Route::get('/personal-tab', [PersonalController::class, 'indexTabulator'])->name('personal.tabulator')->middleware(['can:ver-personal']);
    Route::get('personal/historial-actualizaciones', 
    [App\Http\Controllers\PersonalController::class, 
    'historialActualizaciones']
    )->name('personal.historial-actualizaciones');
    
    Route::get('/personal/data', [PersonalController::class, 'getData'])->name('personal.data')->middleware(['can:ver-personal']);
    
        
    Route::get('personal/select2/empresa', [PersonalController::class, 'select2Empresa'])->name('api.personal.select2.empresa');
    Route::get('personal/select2/gerencia', [PersonalController::class, 'select2Gerencia'])->name('api.personal.select2.gerencia');
    Route::get('personal/select2/area', [PersonalController::class, 'select2Area'])->name('api.personal.select2.area');
    Route::get('personal/select2/cargo', [PersonalController::class, 'select2Cargo'])->name('api.personal.select2.cargo');
    Route::get('personal/select2/reporta', [PersonalController::class, 'select2Reporta'])->name('api.personal.select2.reporta');
    Route::post('personal/marcar-seleccionados', [PersonalController::class, 'marcarSeleccionados'])->name('personal.marcar-seleccionados');

    Route::post('campanias/exportar-todos-seleccionados', 
    [CampaniaHasEvaluadoController::class, 'exportarTodosSeleccionados'])
    ->name('campanias.exportarTodosSeleccionados');

    Route::post('campanias/exportar-personal', 
    [CampaniaHasEvaluadoController::class, 'exportarPersonalACampaniaActual'])
    ->name('campanias.exportarPersonalACampaniaActual');
        
    Route::get('personal/select2/empresa', [PersonalController::class, 'select2Empresa'])->name('api.personal.select2.empresa');
    Route::get('personal/select2/gerencia', [PersonalController::class, 'select2Gerencia'])->name('api.personal.select2.gerencia');
    Route::get('personal/select2/area', [PersonalController::class, 'select2Area'])->name('api.personal.select2.area');
    Route::get('personal/select2/cargo', [PersonalController::class, 'select2Cargo'])->name('api.personal.select2.cargo');
    Route::get('personal/select2/reporta', [PersonalController::class, 'select2Reporta'])->name('api.personal.select2.reporta');

    Route::get('organigrama', [App\Http\Controllers\OrgChartController::class, 'index'])->name('organigrama.index');

    // Rutas para actualización de personal
    Route::post('personal/actualizacion-general', 
    [App\Http\Controllers\PersonalController::class, 
    'actualizacionGeneralCompleta']
    )->name('personal.actualizacion-general');

    Route::post('personal/actualizacion-individual/{dni}', 
    [App\Http\Controllers\PersonalController::class, 
    'actualizacionIndividual']
    )->name('personal.actualizacion-individual');

    Route::post('personal/buscar-por-dni', 
    [App\Http\Controllers\PersonalController::class, 
    'buscarPersonalPorDNI']
    )->name('personal.buscar-por-dni');

    Route::get('personal/historial-actualizaciones', 
    [App\Http\Controllers\PersonalController::class, 
    'historialActualizaciones']
    )->name('personal.historial-actualizaciones');

    Route::post('/personal/sync-user-email/{id}', [PersonalController::class, 'syncUserEmail'])->name('campania_has_evaluados.syncUserEmail');
    Route::post('/personal/create-user/{id}', [PersonalController::class, 'createUser'])->name('campania_has_evaluados.createUser');

    Route::get('personal/area/{id}/path', [\App\Http\Controllers\PersonalController::class,'areaPath'])
        ->name('personal.area.path');
    
    Route::prefix('personal')->group(function () {
        // ...existing personal routes...
        Route::post('import/validate',[PersonalController::class,'validateImport'])->name('personal.import.validate');
        Route::get('import/template', [PersonalController::class,'downloadTemplate'])->name('personal.import.template');
        Route::post('import', [PersonalController::class,'importExcel'])->name('personal.import');
    });

    Route::resource('personal', PersonalController::class)->middleware(['can:ver-personal']);

    Route::view('/areas','livewire.areas.index')->name('areas')->middleware(['can:ver-area']);
    Route::view('/capacitaciones','livewire.capacitaciones.index')->name('capacitaciones')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{id}', function ($id) {
        return view('livewire.capacitaciones.index')->with('id', $id);
    })->name('capacitaciones.show')->middleware(['can:ver-capacitacion']);

    Route::view('/mis-capacitaciones','livewire.mis-capacitaciones.index')->name('mis-capacitaciones')->middleware(['can:ver-mis-capacitaciones']);

    Route::get('/capacitaciones/registro/{capacitacion_id}', function ($capacitacion_id) {
        return view('livewire.capacitacion-has-personals.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.personal')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{capacitacion_id}/asistencia', function ($capacitacion_id) {
        return view('livewire.asistenciums.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.asistencia')->middleware(['can:ver-capacitacion']);

    Route::view('/avance-por-personal','livewire.notas-por-personal.index')->name('avance-por-personal')->middleware('can:ver-avance-por-personal');
    
    Route::view('/cargos','livewire.cargos.index')->name('cargos')->middleware(['can:ver-cargo']);
    Route::view('/tipos_de_capacitaciones','livewire.tipo-de-capacitaciones.index')->name('tipo-de-capacitacion')->middleware(['can:ver-tipo-de-capacitacion']);//tipo_de_activos
    Route::view('/empresas','livewire.empresas.index')->name('empresas')->middleware(['can:ver-empresa']);
    
    Route::view('/evaluaciones','livewire.evaluaciones.index')->name('evaluaciones')->middleware(['can:ver-empresa']);
    Route::view('/preguntas','livewire.preguntas.index')->name('preguntas')->middleware(['can:ver-empresa']);
    Route::view('/opciones','livewire.opciones.index')->name('opciones')->middleware(['can:ver-empresa']);
    Route::get('/evaluaciones-de-desempeno/{id}', function ($tipo_de_evaluacion_id) {
        return view('livewire.evaluador-has-evaluados.index')->with('tipo_de_evaluacion_id', $tipo_de_evaluacion_id);
    })->name('evaluacion_de_desempeno')
    ->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::view('/evaluadores','livewire.evaluadores.index')->name('evaluadores')->middleware(['can:ver-empresa']);
    Route::view('/secciones','livewire.secciones.index')->name('secciones')->middleware(['can:ver-empresa']);
    Route::view('/estados-de-plan-de-accion','livewire.estados-de-plan-de-accion.index')->name('estados-de-plan-de-accion')->middleware(['can:ver-estados-de-plan-de-accion']);
    Route::view('/planes-de-accion','livewire.planes-de-accion.index')->name('planes-de-accion')->middleware(['can:ver-planes-de-accion']);
    Route::view('/planes-de-accion','livewire.planes-de-accion.index')->name('planes-de-accion')->middleware(['can:ver-planes-de-accion']);
    
    Route::get('/evaluacion/{tipo_de_evaluacion_id}/{id}', function ($tipo_de_evaluacion_id,$evaluacion_id) {
        $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id',$evaluacion_id)
            ->where('evaluador_has_evaluados.evaluador_id', auth()->user()->personal_id)
            ->when($tipo_de_evaluacion_id == 1, function ($query) {
                return $query->where('evaluador_has_evaluados.realizado', null);
            })
            ->leftJoin('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id',$tipo_de_evaluacion_id)
            ->first();
        if ($this->evaluadorHasEvaluado) {            
            if ($tipo_de_evaluacion_id == 1) {
                return view('livewire.evaluacion.index')->with('evaluacion_id', $evaluacion_id);
            } elseif ($tipo_de_evaluacion_id == 2) {
                return view('livewire.objetivos.index')->with('evaluacion_id', $evaluacion_id);
            }
        } else {
            $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id',$evaluacion_id)
            ->leftJoin('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
            ->where('evaluaciones.tipo_de_evaluacion_id',$tipo_de_evaluacion_id)
            ->first();            
            if ($this->evaluadorHasEvaluado) {
                if ($this->evaluadorHasEvaluado->realizado == 1 && $this->evaluadorHasEvaluado->tipo_de_evaluacion_id == 1) {
                    return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'Ya evaluó a este empleado');
                } else if ($this->evaluadorHasEvaluado->evaluador_id != auth()->user()->personal_id) {
                    return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'No tiene permisos para evaluar este personal');
                }
            } else {
            return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'No se encuentra registrada esta evaluación');
            }
        }
    })->name('evaluacion.show')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::view('/respuestas','livewire.respuestas.index')->name('respuestas')->middleware(['can:ver-empresa']);

    Route::view('/objetivos-precargados','livewire.objetivos-precargados.index')->name('objetivos-precargados')->middleware(['can:ver-objetivos-precargados']);

    Route::view('/objetivos','livewire.objetivos-lista.index')->name('objetivos')->middleware(['can:ver-empresa']);
    Route::get('/planes-de-mejora/{ingreso}', function ($ingreso) {
        return view('livewire.planes-de-mejora.index')->with('ingreso', $ingreso);
    })->name('planes-de-mejora.ingreso')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::get('/planes-de-mejora/{dashboard}/{empleado_id}', function ($dashboard, $empleado_id) {
        return view('livewire.planes-de-mejora.index')->with('dashboard', $dashboard)->with('empleado_id', $empleado_id);
    })->name('planes-de-mejora')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::view('/seguimiento_evaluadores','livewire.seguimiento-evaluadores.index')->name('seguimiento_evaluadores')->middleware(['can:ver-empresa']);
    Route::view('/seguimiento_evaluados','livewire.seguimiento-evaluados.index')->name('seguimiento_evaluados')->middleware(['can:ver-empresa']);

    Route::view('/sedes','livewire.sedes.index')->name('sedes')->middleware(['can:ver-sede']);
    Route::view('/gerencias','livewire.gerencias.index')->name('gerencias')->middleware(['can:ver-gerencia']);
    Route::view('/temas','livewire.temas.index')->name('temas')->middleware(['can:ver-tema']); //marca
    Route::view('/modalidades','livewire.modalidades.index')->name('modalidades')->middleware(['can:ver-modalidad']);//modelo
    Route::view('/estados','livewire.statuses.index')->name('estados')->middleware(['can:ver-estado']);
    Route::view('/planillas','livewire.planillas.index')->name('planillas')->middleware(['can:ver-planilla']);
    // Route::view('/vigencia','livewire.vigenciums.index')->name('vigencia')->middleware(['can:ver-vigencia']);
    // Route::view('/motivo_baja','livewire.baja-motivos.index')->name('motivo-bajas')->middleware(['can:ver-motivo-baja']);
    // Route::view('/tipo_asignacion','livewire.asignacion-tipos.index')->name('tipo_asignaciones')->middleware(['can:ver-tipo-asignacion']);
    Route::view('/asistencias','livewire.asistenciums.reporte')->name('asistencias')->middleware(['can:ver-asistencia']);
    // Route::view('/devoluciones','livewire.devoluciones.index')->name('devoluciones')->middleware(['can:ver-devolucion']);
    Route::view('/emails/templates/send-invoice','emails.templates.send-invoice')->name('mail-template');

    Route::view('/reporte-de-activos','livewire.reporte-de-activos.index')->name('reporte-de-activos')->middleware(['can:ver-reporte-de-activos']);
    Route::view('/reporte-por-tipo-de-activos','livewire.reporte-por-tipo-de-activos.index')->name('reporte-por-tipo-de-activos')->middleware(['can:ver-reporte-por-tipo-de-activos']);
    Route::view('/reporte-por-estado-de-activos','livewire.reporte-por-estado-de-activos.index')->name('reporte-por-estado-de-activos')->middleware(['can:ver-reporte-por-estado-de-activos']);
    
    Route::get('/pdfprueba', function () {
        $asignacion_guardada = Asignacione::where('id',16)
        ->with(
            'activos_asignados',
            'activos_asignados.activo',
            'activos_asignados.performance',
            'activos_asignados.vigencia',
            'activos_asignados.accesorios',
            'personal',
            'area',
            'empresa',
            'sede',
            'responsable',
            'responsable_area',
            'cargo',
            'responsable_cargo'
            )
        ->first()->toArray();
    
        return view('livewire.asignaciones.pdf', ['asignacion_guardada' => $asignacion_guardada]);
    });

    Route::resource('users',UserController::class);
    Route::resource('roles',RolController::class);
    
});
// Auth::routes();
Route::get('/web/capacitaciones/{tipo_user}/{user_id}', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getCapacitaciones'])->name('capacitaciones.getCapacitaciones'); //FALTA MODIFICAR

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Ruta de vista previa de email (solo para testing local)
Route::get('/preview-email', function () {
    $pendientes = [];
    $enDesarrollo = [];

    for ($i = 1; $i <= 2; $i++) {
        $cap = new \stdClass();
        $capacitacion = new \stdClass();
        $tema = new \stdClass();
        $tema->name = "Capacitación de ejemplo #$i";
        $capacitacion->tema = $tema;
        $capacitacion->descripcion = "Descripción de la capacitación de ejemplo #$i. Contenido breve para mostrar en la plantilla de correo.";
        $capacitacion->responsable = 'Responsable Ejemplo';

        $cap->capacitacion = $capacitacion;
        $cap->fecha_inicio = Carbon::now()->subDays(2 + $i);
        $cap->fecha_fin = Carbon::now()->addDays(5 + $i);

        $pendientes[] = $cap;
    }

    for ($i = 1; $i <= 2; $i++) {
        $cap = new \stdClass();
        $capacitacion = new \stdClass();
        $tema = new \stdClass();
        $tema->name = "Curso activo #$i";
        $capacitacion->tema = $tema;
        $capacitacion->responsable = 'Equipo de Formación';

        $cap->capacitacion = $capacitacion;
        $cap->fecha_inicio = Carbon::now()->subDays($i);
        $cap->fecha_fin = Carbon::now()->addDays(10 - $i);

        $enDesarrollo[] = $cap;
    }

    $link = url('/');
    return view('emails.capacitacion_alerts', compact('pendientes','enDesarrollo','link'));
});

// Route::get('/validar-codigo', [App\Http\Controllers\VerificationController::class, 'verifyCode'])->name('verification.verify');

// Route::get('/enviar-correo', [App\Http\Controllers\VerificationController::class, 'enviarCorreo']);
