<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solucione extends Model
{
	use HasFactory;
    // use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'soluciones';

    protected $fillable = ['pregunta_id','opcion_id'];

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class,'pregunta_id','id');
    }

    public function opcion()
    {
        return $this->belongsTo(Opcione::class,'opcion_id','id');
    }
	
}
