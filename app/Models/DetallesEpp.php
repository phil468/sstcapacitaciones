<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallesEpp extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'detalles_epp';

    protected $fillable = ['inspeccion_id','item','nombre_trabajador','dni','cargo','casco_tiene','casco_uso','casco_condicion','zapatos_tiene','zapatos_uso','zapatos_condicion','lentes_tiene','lentes_uso','lentes_condicion','respirador_tiene','respirador_uso','respirador_condicion','protector_auditivo_tiene','protector_auditivo_uso','protector_auditivo_condicion','guantes_tiene','guantes_uso','guantes_condicion','otros'];
	
}
