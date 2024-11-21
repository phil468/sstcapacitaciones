<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspeccione extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'inspecciones';

    protected $fillable = ['empresa_id','area_id','tipo_inspeccion','vigencia_inicio','vigencia_fin','comentario'];
	
}
