<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteEquipoPrimerosAuxilios extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_equipo_primeros_auxilios';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'botiquin',
        'alcohol',
        'agua_oxigenada',
        'gasas',
        'aposito',
        'esparadrapo',
        'venda_elastica',
        'bandas_adhesivas',
        'tijera',
        'guantes_quirurgicos',
        'algodon'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}