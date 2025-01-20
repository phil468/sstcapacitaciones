<?php
namespace App\Models\Inspecciones\Luces;

use App\Models\Area;
use App\Models\Personal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionLuzEmergencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_luces_emergencia';
    protected $keyType = 'string';

    protected $fillable = [
        'empresa_id',
        'razon_social',
        'ruc',
        'domicilio',
        'actividad_economica',
        'num_trabajadores',
        'fecha_hora_inspeccion',
        'lugar',
        'inspector_id',
        'area_id',
    ];

    public function inspectores()
    {
        return $this->belongsToMany(User::class, 'inspeccion_luz_inspectores', 'inspeccion_id', 'user_id');
    }

    public function responsables()
    {
        return $this->belongsToMany(User::class, 'inspeccion_luz_responsables', 'inspeccion_id', 'user_id')->withPivot('cargo');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleInspeccionLuz::class, 'inspeccion_id');
    }

    public function inspector()
    {
        return $this->belongsTo(Personal::class, 'inspector_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}