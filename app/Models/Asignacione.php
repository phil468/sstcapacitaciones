<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asignacione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'asignaciones';

    protected $fillable = ['personal_id','capacitacion_id','fecha_inicio','fecha_fin','intentos_de_evaluacion','realizado','finalizado','created_by','updated_by','deleted_by','estado_de_asignacion_id'];
	
    public function personal()
    {
        return $this->belongsTo(Personal::class,'personal_id','id');
    }

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacione::class,'capacitacion_id','id');
    }

    public function creado_por()
    {
        return $this->belongsTo(Personal::class,'created_by','id');
    }

    public function actualizado_por()
    {
        return $this->belongsTo(Personal::class,'updated_by','id');
    }

    public function eliminado_por()
    {
        return $this->belongsTo(Personal::class,'deleted_by','id');
    }

    public function estado_de_asignacion()
    {
        return $this->belongsTo(EstadoDeAsignacion::class,'estado_de_asignacion_id','id');
    }

}
