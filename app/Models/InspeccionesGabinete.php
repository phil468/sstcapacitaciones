<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionesGabinete extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'inspecciones_gabinetes';
    protected $keyType = 'string';

    protected $fillable = [
        'fecha_inspeccion',
        'hora_inspeccion',
        'inspector_id',
        'area_id',
        'lugar',
        'resultado',
        'firma'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleInspeccionGabinete::class, 'inspeccion_id');
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
