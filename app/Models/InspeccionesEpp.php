<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionesEpp extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'inspecciones_epp';

    protected $fillable = ['numero_inspeccion','inspector','firma_inspector','turno','condicion','riesgo','actividad','fecha'];
	
}
