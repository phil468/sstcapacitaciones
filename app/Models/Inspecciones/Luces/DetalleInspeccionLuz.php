<?php
namespace App\Models\Inspecciones\Luces;

use App\Models\ParteLuzEmergencia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleInspeccionLuz extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalles_inspeccion_luces';

    protected $fillable = [
        'inspeccion_id',
        'area',
        'enciende',
        'buen_estado',
        'buena_iluminacion',
        'buena_ubicacion',
        'conectado',
        'senalizado',
        'parte_reparar'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionLuzEmergencia::class, 'inspeccion_id');
    }

    public function partes()
    {
        return $this->belongsToMany(ParteLuzEmergencia::class, 'detalles_partes_reparar', 'detalle_id', 'parte_id');
    }
}