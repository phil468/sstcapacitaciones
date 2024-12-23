<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionResponsableRegistro extends Model
{
    use HasFactory, SoftDeletes;
    
    public $timestamps = true;

    protected $table = 'inspeccion_responsables_registro';

    protected $fillable = [
        'inspeccion_id', 'personal_id', 'cargo_id', 'fecha', 'firma'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(Inspeccione::class, 'inspeccion_id');
    }
}