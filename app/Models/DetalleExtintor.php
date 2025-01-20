<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleExtintor extends Model
{
    use HasFactory;

    protected $table = 'detalles_extintores';

    protected $fillable = [
        'inspeccion_id',
        'numero_extintor',
        'ubicacion',
        'tipo',
        'peso',
        'anio_fabricacion',
        'serie',
        'fecha_proxima_recarga',
        'fecha_prueba_hidrostatica',
        'lugar_asignado',
        'facil_acceso',
        'senalizacion',
        'pictograma',
        'pasador',
        'precinto',
        'colatin',
        'manometro',
        'presion_optima',
        'cuerpo_estado',
        'boquilla_tobera',
        'manguera',
        'manija_transporte',
        'palanca',
        'tarjeta_control',
        'gabinete',
        'observaciones'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionExtintor::class, 'inspeccion_id');
    }
}