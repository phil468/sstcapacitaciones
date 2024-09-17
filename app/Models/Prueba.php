<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prueba extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'pruebas';

    protected $fillable = ['personal_id','capacitacion_id','puntaje','correctas','incorrectas','fecha_inicio','fecha_fin','duracion','status_id', 'intento'];

    protected $dates = ['fecha_inicio','fecha_fin'];

    public function personal() {
        return $this->belongsTo(Personal::class,'personal_id');
    }

    public function capacitacion() {
        return $this->belongsTo(Capacitacione::class,'capacitacion_id');
    }

    public function estado() {
        return $this->belongsTo(Status::class,'status_id');
    }

    public function preguntas() {
        return $this->belongsToMany(Pregunta::class, 'respuestas', 'prueba_id', 'pregunta_id')
            ->withPivot('personal_id','opcion_id','valor_numerico','valor_texto','capacitacion_id','opcion_correcta_id')
            ->withTimestamps();
    }


}
