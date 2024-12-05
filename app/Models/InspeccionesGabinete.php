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

    protected $fillable = ['fecha_inspeccion','hora_inspeccion','inspector','lugar'];
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gabinetes()
    {
        return $this->hasMany('App\Models\Gabinete', 'inspeccion_id', 'id');
    }
    
}
