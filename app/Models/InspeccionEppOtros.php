<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionEppOtros extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_epp_otros';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_epp_id',
        'name',
        'estado'
    ];

    public function inspeccionEpp()
    {
        return $this->belongsTo(InspeccionesEpp::class, 'inspeccion_epp_id');
    }

    public function detallesEppOtros()
    {
        return $this->hasMany(DetallesEppOtros::class, 'inspeccion_epp_otro_id');
    }
}