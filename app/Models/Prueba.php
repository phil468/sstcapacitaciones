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

    protected $fillable = ['personal_id','capacitacion_id','puntaje','correctas','incorrectas','fecha_inicio','fecha_fin','duracion','status_id'];
	
}
