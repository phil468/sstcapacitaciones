<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteEquipoSeguridad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_equipo_seguridad';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'adhesivos_reflectivos',
        'triangulos_seguridad',
        'conos',
        'tacos_emergencia',
        'extintor_pqs',
        'cable_baterias',
        'cadena_remolque',
        'llave_rueda',
        'llanta_repuesto',
        'gata_hidraulica',
        'ventanas_emergencia',
        'martillos_emergencia'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}