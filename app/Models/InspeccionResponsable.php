<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspeccionResponsable extends Model
{
	use HasFactory;
	
    public $timestamps = true;

    protected $table = 'inspeccionResponsables';

    protected $fillable = ['inspeccion_id','personal_id','cargo'];
	
}
