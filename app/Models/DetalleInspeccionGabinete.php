<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleInspeccionGabinete extends Model
{
    use HasFactory;

    protected $table = 'detalles_inspecciones_gabinetes';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'gabinete_id',
        'numero_gabinete',
        'ubicacion',
        'inspeccion_id',
        'enrollada_correctamente',
        'registro_fotografico_enrollada_correctamente',
        'acoples_estado',
        'registro_fotografico_acoples_estado',
        'limpieza_manguera',
        'registro_fotografico_limpieza_manguera',
        'empaques_estado',
        'registro_fotografico_empaques_estado',
        'pintura_gabinete',
        'registro_fotografico_pintura_gabinete',
        'limpieza_gabinete',
        'registro_fotografico_limpieza_gabinete',
        'vidrio_estado',
        'registro_fotografico_vidrio_estado',
        'senalizacion',
        'registro_fotografico_senalizacion',
        'piton_obstruido',
        'registro_fotografico_piton_obstruido',
        'piton_estado',
        'registro_fotografico_piton_estado',
        'valvula_principal_estado',
        'registro_fotografico_valvula_principal_estado',
        'valvula_principal_abierta',
        'registro_fotografico_valvula_principal_abierta',
        'manometro_estado',
        'registro_fotografico_manometro_estado',
        'valvula_angular_estado',
        'registro_fotografico_valvula_angular_estado',
        'observaciones'
    ];

    public function gabinete()
    {
        return $this->belongsTo(Gabinete::class, 'gabinete_id');
    }

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionesGabinete::class, 'inspeccion_id');
    }
}