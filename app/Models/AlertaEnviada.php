<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertaEnviada extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'alerta_enviadas';

    protected $fillable = ['capacitacion_has_personal_id','fecha_envio'];

    public function capacitacion_has_personal()
    {
        return $this->belongsTo(CapacitacionHasPersonal::class,'capacitacion_has_personal_id','id');
    }

    protected $dates = ['fecha_envio'];

}
