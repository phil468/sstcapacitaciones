<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificacionEnviada extends Model
{
    use HasFactory;

    protected $table = 'notificaciones_enviadas';

    protected $fillable = [
        'capacitacion_id',
        'personal_id',
    ];

}
