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
            'inspeccion_id',
            'descripcion',
            'nivel_riesgo',
            'registro_fotografico',
            'accion_a_tomar',
            'responsable_id',
            'cargo_id',
            'estado',
            'fecha_ejecucion'
        ];
	
    public function inspeccion()
    {
        return $this->belongsTo(Inspeccione::class, 'inspeccion_id');
    }

    public function responsable()
    {
        return $this->belongsTo(Personal::class, 'responsable_id');
    }
    
}
