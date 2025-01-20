<?php
namespace App\Models\Inspecciones\Luces;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParteLuzEmergencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'partes_luces_emergencia';

    protected $fillable = [
        'name'
    ];
}