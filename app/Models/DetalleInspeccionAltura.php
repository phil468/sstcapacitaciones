<?php
namespace App\Models;

use App\Models\Area;
use App\Models\InspeccionAltura;
use App\Models\Inspecciones\Luces\ParteLuzEmergencia as LucesParteLuzEmergencia;
use App\Models\ParteLuzEmergencia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleInspeccionAltura extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalles_inspeccion_altura';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'area_id',
        'costuras',
        'hebillas_argollas',
        'linea_de_vida',
        'linea_de_anclaje',
        'fajas',
        'amortiguador_de_impacto',
        'grilletes_seguros',
        'libre_de_hebras_cortadas',
        'libre_de_torceduras',
        'guardacabo_de_empalme',
        'libre_de_cortaduras',
        'rotulacion_de_carga_maxima',
        'correctamente_almacenada',
        'libre_de_quemaduras_visibles',
        'libre_de_deterioro',
        'libre_de_astillamiento',
        'libre_de_uniones_rotas',
        'no_tiene_nudos',
        'desgaste_abrasivo_excesivo',
        'hilos_en_buenas_condiciones',
        'pasador_en_buenas_condiciones',
        'rotulacion_de_carga_maxima2',
        'indicacion_de_carga_maxima',
        'seguro_de_cierre',
        'distorsion_del_gancho',
        'poleas_en_buen_estado',
        'codigo_de_arnes',
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionAltura::class, 'inspeccion_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}