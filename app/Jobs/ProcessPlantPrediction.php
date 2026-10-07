<?php

namespace App\Jobs;

use App\Models\Disease;
use App\Models\Prediction;
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
    public int $timeout = 60;

    public function __construct(
        public int $predictionId
    ) {
    }

    public function handle(PlantDiseaseAiService $ai): void
    {
        $prediction = Prediction::find($this->predictionId);

        if (!$prediction) {
            return;
        }

        $prediction->update([
            'status' => 'Processing AI analysis...',
        ]);

        $this->notifyRealtime(
            $prediction->id,
            'processing'
        );

        try {

            $absolutePath = Storage::disk('public')
                ->path($prediction->image_path);

            if (!file_exists($absolutePath)) {
                throw new \RuntimeException(
                    'Uploaded leaf image was not found.'
                );
            }

            $file = new UploadedFile(
                $absolutePath,
                basename($absolutePath),
                mime_content_type($absolutePath) ?: 'image/jpeg',
                null,
                true
            );

            $result = $ai->predict($file);

            $disease = Disease::where(
                'class_key',
                $result['predicted_class']
            )
            ->where('is_active', true)
            ->first();

            $prediction->update([
                'disease_id' => $disease?->id,
                'predicted_class' => $result['predicted_class'],
                'confidence' => $result['confidence'],
                'top_predictions' => $result['top_predictions'],
                'model_version' => $result['model_version'],
                'is_demo' => $result['is_demo'] ?? false,

                'status' => $ai->confidenceLabel(
                    $result['confidence']
                ),

                'raw_response' => $result['raw'] ?? $result,
            ]);

            $this->notifyRealtime(
                $prediction->id,
                'completed'
            );

        } catch (Throwable $e) {

            report($e);

            $prediction->update([
                'status' => 'AI analysis failed',
                'raw_response' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            $this->notifyRealtime(
                $prediction->id,
                'failed'
            );
        }
    }

    private function notifyRealtime(
        int $predictionId,
        string $status
    ): void {
        try {

            $url = rtrim(
                config(
                    'services.realtime.url',
                    'http://127.0.0.1:3001'
                ),
                '/'
            );

            Http::timeout(2)->post(
                $url . '/notify/prediction',
                [
                    'prediction_id' => $predictionId,
                    'status' => $status,
                ]
            );

        } catch (Throwable $e) {
            report($e);
        }
    }
}