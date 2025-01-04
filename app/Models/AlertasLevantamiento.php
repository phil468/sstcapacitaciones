<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertasLevantamiento extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'alertas_levantamiento';

    protected $fillable = ['resultado_inspeccion_id','resultado_inspeccion_uuid','registro_fotografico','levantado','notificado'];

    public function resultado_inspeccion()
    {
        return $this->belongsTo(ResultadosInspeccion::class, 'resultado_inspeccion_uuid', 'uuid');
    }

    
	
}
