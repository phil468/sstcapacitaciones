<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteDocumentacionVehiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_documentacion_vehiculo';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'soat_vigente',
        'num_asientos_soat',
        'fecha_vencimiento_soat',
        'revision_tecnica_vigente',
        'fecha_vencimiento_revision_tecnica',
        'permiso_circulacion_vigente',
        'fecha_vencimiento_permiso_circulacion',
        'tarjeta_identificacion_vehicular',
        'num_asientos_tarjeta'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}