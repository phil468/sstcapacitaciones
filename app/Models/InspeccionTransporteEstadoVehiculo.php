<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteEstadoVehiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_estado_vehiculo';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'parabrisas',
        'espejos_laterales',
        'espejo_central',
        'ventanas_integras',
        'ventanas_operativas',
        'ventanas_cortinas',
        'neumaticos_delanteros',
        'neumaticos_posteriores',
        'asientos',
        'pasillo',
        'cinturon_conductor',
        'rotulo_ruta'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}