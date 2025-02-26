<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Epp extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'epps';

    protected $fillable = [
        'name',
        'estado',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = mb_strtoupper(trim($value));
    }

    public function getNameAttribute($value)
    {
        return mb_strtoupper($value);
    }
    
    public function scopeActivo($query)
    {
        return $query->where('estado',1);
    }
}