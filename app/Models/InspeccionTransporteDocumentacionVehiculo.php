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
        'soat_vencimiento',
        'revision_tecnica_vigente',
        'revision_tecnica_vencimiento',
        'permiso_circulacion_vigente',
        'permiso_circulacion_vencimiento',
        'tarjeta_identificacion_vehicular',
        'numero_asientos'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}