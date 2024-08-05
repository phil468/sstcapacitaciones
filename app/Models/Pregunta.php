<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pregunta extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'preguntas';

    protected $fillable = [
        'pregunta',
        'tipo_de_pregunta_id',
        'numero_orden',
        'capacitacion_id',
        ];

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class,'pregunta_id','id');
    }

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacione::class,'capacitacion_id','id');
    }

    public function tipo_de_pregunta()
    {
        return $this->belongsTo(TipoDePregunta::class,'tipo_de_pregunta_id','id');
    }

    public function opciones()
    {
        return $this->hasMany(Opcione::class,'pregunta_id','id');
    }

    public function solucion()
    {
        return $this->hasOne(Solucione::class,'pregunta_id','id');
    }

    
	
}
