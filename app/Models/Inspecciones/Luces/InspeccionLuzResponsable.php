<?php
namespace App\Models\Inspecciones\Luces;

use App\Models\Cargo;
use App\Models\Personal;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionLuzResponsable extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_luz_responsables';

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'personal_id',
        'cargo_id',
        'fecha',
        'firma'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionLuzEmergencia::class, 'inspeccion_id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }
}