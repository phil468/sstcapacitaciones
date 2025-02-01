<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteFuncionamientoVehiculo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_funcionamiento_vehiculo';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'luces_altas',
        'luces_bajas',
        'luces_direccionales_delanteras',
        'luces_direccionales_posteriores',
        'luces_emergencia',
        'luces_neblineros',
        'luces_alarma_retroceso',
        'velocimetro',
        'sistema_frenos',
        'tablero_combustible',
        'limpia_parabrisas',
        'puertas_acceso',
        'claxon',
        'luces_salon'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}