<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteDocumentacionConductor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_documentacion_conductor';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'dni_vigente',
        'fecha_vencimiento_dni',
        'brevete_validez',
        'fecha_vencimiento_brevete',
        'brevete_categoria',
        'medidas_preventivas'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}