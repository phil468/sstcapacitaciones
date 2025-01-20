<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionExtintor extends Model
{
    use HasFactory;

    protected $table = 'inspecciones_extintores';

    protected $fillable = [
        'inspector',
        'firma',
        'fecha',
        'hora',
        'lugar'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleExtintor::class, 'inspeccion_id');
    }
}