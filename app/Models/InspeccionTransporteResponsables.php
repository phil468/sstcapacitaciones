<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InspeccionTransporteResponsables extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inspeccion_transporte_responsables';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'inspeccion_id',
        'personal_id',
        'cargo_id',
        'firma',
        'fecha'
    ];

    public function inspeccion()
    {
        return $this->belongsTo(InspeccionTransporte::class, 'inspeccion_id');
    }
}