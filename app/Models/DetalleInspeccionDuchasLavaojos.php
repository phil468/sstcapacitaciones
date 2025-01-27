<?php
namespace App\Models;

use App\Models\Area;
use App\Models\InspeccionAltura;
use App\Models\Inspecciones\Luces\ParteLuzEmergencia as LucesParteLuzEmergencia;
use App\Models\ParteLuzEmergencia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleInspeccionDuchasLavaojos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalles_inspeccion_duchas_lavaojos';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'area_id',
        'a',
        'b',
        'c',
        'd',
        'e',
        'f',
        'g',
        'h',
        'i',
        'j',

    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionDuchasLavaojos::class, 'inspeccion_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}