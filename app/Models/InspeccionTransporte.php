<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporte extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'empresa_id',
        'razon_social',
        'ruc',
        'domicilio',
        'tipo_actividad_economica',
        'num_trabajadores',
        'inspector_id',
        'lugar',
        'observaciones_1',
        'observaciones_2',
        'firma',
        'firma_conductor',
        'area_id',
        'empresa_de_transporte'
    ];

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
    
    public function responsables()
    {
        return $this->hasMany(InspeccionTransporteResponsables::class, 'inspeccion_id');
    }

    public function informacionConductor()
    {
        return $this->hasOne(InspeccionTransporteInformacionConductor::class, 'inspeccion_id');
    }

    public function funcionamientoVehiculo()
    {
        return $this->hasOne(InspeccionTransporteFuncionamientoVehiculo::class, 'inspeccion_id');
    }

    public function estadoVehiculo()
    {
        return $this->hasOne(InspeccionTransporteEstadoVehiculo::class, 'inspeccion_id');
    }

    public function documentacionVehiculo()
    {
        return $this->hasOne(InspeccionTransporteDocumentacionVehiculo::class, 'inspeccion_id');
    }

    public function documentacionConductor()
    {
        return $this->hasOne(InspeccionTransporteDocumentacionConductor::class, 'inspeccion_id');
    }

    public function equipoSeguridad()
    {
        return $this->hasOne(InspeccionTransporteEquipoSeguridad::class, 'inspeccion_id');
    }

    public function equipoPrimerosAuxilios()
    {
        return $this->hasOne(InspeccionTransporteEquipoPrimerosAuxilios::class, 'inspeccion_id');
    }
}