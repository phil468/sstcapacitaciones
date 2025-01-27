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

    protected $fillable = [
        'inspeccion_id',
        'item',
        'nombre_trabajador',
        'dni_personal',
        'cargo_personal',
        'casco_tiene',
        'casco_uso',
        'casco_condicion',
        'zapatos_tiene',
        'zapatos_uso',
        'zapatos_condicion',
        'lentes_tiene',
        'lentes_uso',
        'lentes_condicion',
        'respirador_tiene',
        'respirador_uso',
        'respirador_condicion',
        'protector_auditivo_tiene',
        'protector_auditivo_uso',
        'protector_auditivo_condicion',
        'guantes_tiene',
        'guantes_uso',
        'guantes_condicion',
        'personal_id',
        'cargo_id',
        ];
    
    public function inspeccion()
    {
        return $this->belongsTo(InspeccionesEpp::class, 'inspeccion_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function detalles_epp_otros()
    {
        return $this->hasOne(DetallesEppOtros::class, 'detalle_epp_id');
    }

	
}
