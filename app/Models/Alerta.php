<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alerta extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'alertas';

    protected $fillable = ['name','estado','dias','condicion','campo'];
	
}
