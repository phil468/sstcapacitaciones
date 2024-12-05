<?php
namespace App\Models\Inspecciones\Luces;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionLuzEmergencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_luces_emergencia';

    protected $fillable = [
        'empresa_id',
        'razon_social',
        'ruc',
        'domicilio',
        'actividad_economica',
        'num_trabajadores',
        'fecha_hora_inspeccion',
        'lugar'
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
}