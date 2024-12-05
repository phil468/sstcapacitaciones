<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gabinete extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'gabinetes';

    protected $fillable = ['numero_gabinete','ubicacion','inspeccion_id','enrollada_correctamente','acoples_estado','limpieza_manguera','empaques_estado','pintura_gabinete','limpieza_gabinete','vidrio_estado','senalizacion','piton_obstruido','piton_estado','valvula_principal_estado','valvula_principal_abierta','manometro_estado','valvula_angular_estado','observaciones'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function inspeccionesGabinete()
    {
        return $this->hasOne('App\Models\InspeccionesGabinete', 'id', 'inspeccion_id');
    }
    
}
