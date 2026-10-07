<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Feedback extends Model
{
    use HasFactory;
    protected $fillable = ['prediction_id','user_id','is_correct','comment'];
    protected function casts(): array { return ['is_correct' => 'boolean']; }
    public function prediction(): BelongsTo { return $this->belongsTo(Prediction::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
