<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionArea extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'inspeccion_areas';

    protected $fillable = ['inspeccion_id','area_id'];

    public function inspeccion()
    {
        return $this->belongsTo(Inspeccione::class, 'inspeccion_id');
    }
	
}
