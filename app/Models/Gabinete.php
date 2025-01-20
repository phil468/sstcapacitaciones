<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gabinete extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'gabinetes';

    protected $fillable = [
        'name',
        'estado',
        'codigo'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleInspeccionGabinete::class, 'gabinete_id');
    }
    
}
