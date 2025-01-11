<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConfiguracionAlertaInspeccionSST extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'config_alertas_inspecciones_sst';

    protected $fillable = [
        'name',
        'estado',
        'dias',
        'condicion',
        'campo'
    ];
}