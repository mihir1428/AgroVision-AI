<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Prediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'disease_id',
        'image_path',
        'predicted_class',
        'confidence',
        'confidence_label',
        'top_predictions',
        'model_version',
        'is_demo',
        'status',
        'failure_reason',
        'started_at',
        'completed_at',
        'raw_response',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'float',
            'top_predictions' => 'array',
            'raw_response' => 'array',
            'is_demo' => 'boolean',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function disease(): BelongsTo { return $this->belongsTo(Disease::class); }
    public function feedback(): HasOne { return $this->hasOne(Feedback::class); }

    public function isQueued(): bool { return $this->status === 'queued'; }
    public function isProcessing(): bool { return $this->status === 'processing'; }
    public function isCompleted(): bool { return $this->status === 'completed'; }
    public function isFailed(): bool { return $this->status === 'failed'; }
    public function isTerminal(): bool { return $this->isCompleted() || $this->isFailed(); }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'queued' => 'Queued for AI analysis',
            'processing' => 'AI analysis in progress',
            'completed' => $this->confidence_label ?: 'Analysis completed',
            'failed' => 'AI analysis failed',
            default => ucfirst((string) $this->status),
        };
    }
}
