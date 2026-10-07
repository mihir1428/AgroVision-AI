<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ModelVersion extends Model
{
    protected $fillable = ['version','architecture','accuracy','precision','recall','f1_score','notes','deployed_at','is_active'];
    protected function casts(): array { return ['accuracy'=>'float','precision'=>'float','recall'=>'float','f1_score'=>'float','deployed_at'=>'datetime','is_active'=>'boolean']; }
}
