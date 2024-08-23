<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionAccessLog extends Model
{
    use HasFactory;

    protected $fillable = ['capacitacion_id', 'personal_id', 'sesion_id', 'numero_de_sesion', 'accessed_at', 'numero_de_evaluacion', 'ingreso_a_capacitacion', 'ingreso_a_evaluacion'];

}
