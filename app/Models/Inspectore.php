<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspectore extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'inspectores';

    protected $fillable = ['personal_id','estado'];
	
    public function personal()
    {
        return $this->belongsTo(Personal::class, 'personal_id');
    }
    
}
