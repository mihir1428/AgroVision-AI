<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Prediction extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','disease_id','image_path','predicted_class','confidence','top_predictions','model_version','is_demo','status','raw_response'];
    protected function casts(): array { return ['confidence' => 'float','top_predictions' => 'array','raw_response' => 'array','is_demo' => 'boolean']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function disease(): BelongsTo { return $this->belongsTo(Disease::class); }
    public function feedback(): HasOne { return $this->hasOne(Feedback::class); }
}
