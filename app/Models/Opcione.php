<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Opcione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'opciones';

    protected $fillable = ['pregunta_id','opcion','valor','optionid'];
	
    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class,'pregunta_id','id');
    }

    public function respuestas()
    {
        return $this->hasMany(Respuesta::class,'opcion_id','id');
    }

}
