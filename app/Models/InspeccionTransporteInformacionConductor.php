<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteInformacionConductor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_informacion_conductor';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'conductor',
        'placa',
        'numero_asientos',
        'numero_brevete',
        'categoria_brevete',
        'ruta',
        'omnibus',
        'otros',
        'fecha',
        'hora',
        'otros'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}