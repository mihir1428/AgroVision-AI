<?php

namespace App\Jobs;

use App\Models\Disease;
use App\Models\Prediction;
use App\Notifications\PredictionStatusNotification;
use App\Services\PlantDiseaseAiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessPlantPrediction implements ShouldQueue
{
    use Queueable;

    public int $tries = 2;
    public int $timeout = 120;
    public int $backoff = 5;

    public function __construct(public int $predictionId) {}

    public function handle(PlantDiseaseAiService $ai): void
    {
        $prediction = Prediction::with('user')->find($this->predictionId);

        if (!$prediction) {
            return;
        }

        $prediction->update([
            'status' => 'processing',
            'failure_reason' => null,
            'started_at' => $prediction->started_at ?: now(),
        ]);

        $this->notifyRealtime($prediction, 'processing');

        $absolutePath = Storage::disk('public')->path($prediction->image_path);

        if (!file_exists($absolutePath)) {
            throw new \RuntimeException('Uploaded leaf image was not found.');
        }

        $file = new UploadedFile(
            $absolutePath,
            basename($absolutePath),
            mime_content_type($absolutePath) ?: 'image/jpeg',
            null,
            true
        );

        $result = $ai->predict($file);

        $disease = Disease::where('class_key', $result['predicted_class'])
            ->where('is_active', true)
            ->first();

        $prediction->update([
            'disease_id' => $disease?->id,
            'predicted_class' => $result['predicted_class'],
            'confidence' => $result['confidence'],
            'confidence_label' => $ai->confidenceLabel($result['confidence']),
            'top_predictions' => $result['top_predictions'],
            'model_version' => $result['model_version'],
            'is_demo' => $result['is_demo'] ?? false,
            'status' => 'completed',
            'failure_reason' => null,
            'completed_at' => now(),
            'raw_response' => $result['raw'] ?? $result,
        ]);

        $prediction->refresh()->load(['user', 'disease']);
        $notification = $this->storeNotification($prediction, 'completed');
        $this->notifyRealtime($prediction, 'completed', $notification);
    }

    public function failed(?Throwable $exception): void
    {
        $prediction = Prediction::with(['user', 'disease'])->find($this->predictionId);

        if (!$prediction) {
            return;
        }

        $prediction->update([
            'status' => 'failed',
            'failure_reason' => $exception?->getMessage() ?: 'Unknown AI processing error.',
            'completed_at' => now(),
            'raw_response' => [
                'error' => $exception?->getMessage() ?: 'Unknown AI processing error.',
            ],
        ]);

        $prediction->refresh()->load(['user', 'disease']);
        $notification = $this->storeNotification($prediction, 'failed');
        $this->notifyRealtime($prediction, 'failed', $notification);
    }

    private function storeNotification(Prediction $prediction, string $status): ?array
    {
        if (!$prediction->user) {
            return null;
        }

        $notification = new PredictionStatusNotification($prediction, $status);
        $prediction->user->notify($notification);

        $data = $notification->payload();

        return [
            'id' => $notification->id,
            'title' => $data['title'],
            'message' => $data['message'],
            'status' => $data['status'],
            'prediction_id' => $prediction->id,
            'url' => $data['url'],
            'created_at' => now()->toIso8601String(),
        ];
    }

    private function notifyRealtime(Prediction $prediction, string $status, ?array $notification = null): void
    {
        try {
            $url = rtrim(config('services.realtime.url', 'http://127.0.0.1:3001'), '/');

            Http::timeout(2)->post($url.'/notify/prediction', [
                'prediction_id' => $prediction->id,
                'user_id' => $prediction->user_id,
                'status' => $status,
                'notification' => $notification,
                'unread_count' => $prediction->user?->unreadNotifications()->count(),
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }
}
