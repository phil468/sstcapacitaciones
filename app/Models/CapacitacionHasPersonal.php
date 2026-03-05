<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CapacitacionHasPersonal extends Model
{
	use HasFactory;
    // use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'capacitacion_has_personal';

    protected $fillable = 
    [
        'personal_id',
        'capacitacion_id',
        'active',
        'observaciones',
        'empresa_id',
        'gerencia_id',
        'area_id',
        'cargo_id',
        'planilla_id',
        'sede_id',
        'tipo_de_trabajador_id',
        'tipo_de_personal_id',
        'capacitacion_has_personal_id',
        'synced',
        'fecha_inicio',
        'fecha_fin',
        'intentos_de_evaluacion',
    ];

    protected $dates = [
        'fecha_inicio',
        'fecha_fin',
    ];
    
    /// quiero deveolver un valor verdadero si hoy se encuentra entre $evaluador_has_evaluado->evaluacion->fecha_inicio y $evaluador_has_evaluado->evaluacion->fecha_fin
    public function getActivaAttribute()
    {
        return $this->fecha_inicio <= now() && $this->fecha_fin >= now();
    }

    public function getFinalizadaAttribute()
    {
        return $this->fecha_fin < now();
    }

    public function getIntentosAgotadosAttribute()
    {
        return ($this->numero_de_intento_actual > 0 && $this->numero_de_intento_actual >= ($this->intentos_de_evaluacion ?? $this->capacitacion->intentos_de_evaluacion ?? 1));
    }
	
    public function personal()
    {
        return $this->belongsTo(Personal::class,'personal_id','id');
    }

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacione::class,'capacitacion_id','id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class,'empresa_id','id');
    }
    
    public function gerencia()
    {
        return $this->belongsTo(Gerencia::class,'gerencia_id','id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class,'area_id','id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class,'cargo_id','id');
    }

    public function planilla()
    {
        return $this->belongsTo(Planilla::class,'planilla_id','id');
    }

    public function sede()
    {
        return $this->belongsTo(Sede::class,'sede_id','id');
    }

    public function tipo_de_trabajador()
    {
        return $this->belongsTo(TipoDeTrabajador::class,'tipo_de_trabajador_id','id');
    }

    public function tipo_de_personal()
    {
        return $this->belongsTo(TipoDePersonal::class,'tipo_de_personal_id','id');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesione::class,'capacitacion_has_personal_id','id');
    }

    public function alertasEnviadas()
    {
        return $this->hasMany(AlertaEnviada::class,'capacitacion_has_personal_id','id');
    }

    // Nuevo método para obtener la nota de la última prueba finalizada
    public function obtenerNota()
    {
        $prueba = Prueba::where('capacitacion_id', $this->capacitacion_id)
                        ->where('personal_id', $this->personal_id)
                        ->where('status_id', 2)
                        ->orderBy('intento', 'desc')
                        ->first();
        return $prueba ? $prueba->puntaje : 0.00;
    }

    // Método para obtener el valor de intentos_de_evaluacion
    public function getIntentosDeEvaluacionAttribute()
    {
        return $this->attributes['intentos_de_evaluacion'] ?? $this->capacitacion->intentos_de_evaluacion;
    }

    public function getNumeroDeIntentoActualAttribute()
    {
        $prueba = Prueba::where('capacitacion_id', $this->capacitacion_id)
                        ->where('personal_id', $this->personal_id)
                        ->where('status_id', 2)
                        ->orderBy('intento', 'desc')
                        ->first();
        return $prueba ? $prueba->intento : null;
    }

    public function getNotaAttribute()
    {
        return $this->obtenerNota();
    }

    public function getNotaFinalAttribute()
    {
        return $this->obtenerNota();
    }

    /**
     * Scope para filtrar por personal con/sin usuario
     * @param $query
     * @param int $tieneUsuario (1 = tiene usuario, 0 = no tiene usuario)
     */
    public function scopeConUsuario($query, $tieneUsuario)
    {
        if ($tieneUsuario == 1) {
            // Filtrar solo personal que TIENE usuario
            return $query->whereHas('personal.user');
        } else {
            // Filtrar solo personal que NO TIENE usuario
            return $query->whereHas('personal', function ($q) {
                $q->doesntHave('user');
            });
        }
    }

}
