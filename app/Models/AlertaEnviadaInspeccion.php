<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertaEnviadaInspeccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'alertas_enviadas_inspecciones';

    protected $fillable = [
        'resultado_inspeccion_id',
        'fecha_envio'
    ];

    public function resultado_inspeccion()
    {
        return $this->belongsTo(ResultadosInspeccion::class, 'resultado_inspeccion_id');
    }
}