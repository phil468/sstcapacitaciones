<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ResultadosInspeccion extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'resultados_inspeccion';

    protected $fillable = [
        'uuid',
        'inspeccion_id',
        'descripcion',
        'nivel_riesgo',
        'registro_fotografico',
        'accion_a_tomar',
        'responsable_id',
        'cargo_id',
        'estado',
        'fecha_ejecucion',
        'notificado'
    ];
	
    public function inspeccion()
    {
        return $this->belongsTo(Inspeccione::class, 'inspeccion_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Personal::class, 'responsable_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function levantamientos()
    {
        return $this->hasMany(AlertasLevantamiento::class, 'resultado_inspeccion_uuid', 'uuid');
    }

    public function levantamiento()
    {
        // el ultimo levantamiento ingresado
        return $this->hasOne(AlertasLevantamiento::class, 'resultado_inspeccion_uuid', 'uuid')->latest();
        
    }
    
}
