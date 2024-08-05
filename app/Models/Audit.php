<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
	use HasFactory;
    use SoftDeletes;
	
    public $timestamps = true;

    protected $table = 'audits';

    protected $fillable = ['user_type','user_id','event','auditable_type','auditable_id','old_values','new_values','url','ip_address','user_agent','tags'];
	
}
