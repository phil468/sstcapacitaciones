<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificacionesEnviada extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'notificaciones_enviadas';

    protected $fillable = ['capacitacion_id','personal_id'];

    public function capacitacion()
    {
        return $this->belongsTo(Capacitacione::class,'capacitacion_id','id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class,'personal_id','id');
    }
	
}
