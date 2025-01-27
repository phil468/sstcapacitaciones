<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetallesEppOtros extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalles_epp_otros';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'detalle_epp_id',
        'inspeccion_epp_otro_id',
        'tiene',
        'uso',
        'condicion'
    ];

    public function detalleEpp()
    {
        return $this->belongsTo(DetallesEpp::class, 'detalle_epp_id');
    }

    public function inspeccionEppOtro()
    {
        return $this->belongsTo(InspeccionEppOtros::class, 'inspeccion_epp_otro_id');
    }
}