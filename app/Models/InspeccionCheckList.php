<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionCheckList extends Model
{
    use HasFactory;

    protected $table = 'check_list_inspecciones_sst';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'empresa_id',
        'razon_social',
        'ruc',
        'domicilio',
        'actividad_economica',
        'num_trabajadores',
        'fecha_hora_inspeccion',
        'inspector_id',
        'area_id',
        'firma',
        'observaciones',
        'lugar',
    ];

    protected $dates = [
        'fecha_hora_inspeccion'
    ];    

    public function detalles()
    {
        return $this->hasOne(DetallesCheckList::class, 'inspeccion_id');
    }

    public function inspector()
    {
        return $this->belongsTo(Personal::class, 'inspector_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }
}