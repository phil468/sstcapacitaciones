<?php
namespace App\Models;

use App\Models\Area;
use App\Models\Empresa;
use App\Models\DetalleInspeccionAltura;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionDuchasLavaojos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspecciones_duchas_lavaojos';
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
        'fecha',
        'hora',
        'inspector_id',
        'area_id'
    ];

    // protected $dates = [
    //     'fecha_hora_inspeccion'
    // ];    

    public function detalles()
    {
        return $this->hasMany(DetalleInspeccionDuchasLavaojos::class, 'inspeccion_id');
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