<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstadoDeAsignacion extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'estado_de_asignacion';

    protected $fillable = ['name','color','icon','estado'];
	
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }

    public function asignaciones()
    {
        return $this->hasMany(Asignacione::class,'estado_de_asignacion_id','id');
    }
}
