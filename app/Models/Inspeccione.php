<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspeccione extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'inspecciones';

    protected $fillable = ['empresa_id',
        'tipo_inspeccion',
        'vigencia_inicio',
        'vigencia_fin',
        'comentario',
        'razon_social',
        'ruc',
        'domicilio',
        'actividad_economica',
        'numero_registro',
        'tipo_inspeccion_otro',
        'fecha_inspeccion',
        'hora_inspeccion'
    ];
    
    public function areas()
    {
        return $this->belongsToMany(Area::class, 'inspeccion_areas', 'inspeccion_id', 'area_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    public function responsables_inspeccion()
    {
        return $this->belongsToMany(Personal::class, 'inspeccion_inspectores', 'inspeccion_id', 'personal_id');
    }

    public function responsables_area()
    {
        return $this->belongsToMany(Personal::class, 'inspeccion_responsables', 'inspeccion_id', 'personal_id');
    }

    public function detalles()
    {
        return $this->hasMany(ResultadosInspeccion::class, 'inspeccion_id');
    }

    public function responsables_registro()
    {
        return $this->hasMany(InspeccionResponsableRegistro::class, 'inspeccion_id');
    }

}
