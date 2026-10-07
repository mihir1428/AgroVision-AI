<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Disease extends Model
{
    use HasFactory;
    protected $fillable = ['class_key','crop_name','disease_name','scientific_name','description','symptoms','cause','prevention','management','bangla_description','bangla_symptoms','bangla_management','is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }
    public function predictions(): HasMany { return $this->hasMany(Prediction::class); }
}
