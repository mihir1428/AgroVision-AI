<?php
namespace App\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use RuntimeException;
class PlantDiseaseAiService
{
    public function predict(UploadedFile $image): array
    {
        try {
            $response = Http::timeout(config('services.ai.timeout', 30))
                ->attach('file', fopen($image->getRealPath(), 'r'), $image->getClientOriginalName())
                ->post(rtrim(config('services.ai.url'), '/').'/predict');
            if ($response->successful()) {
                $data = $response->json();
                $data['is_demo'] = (bool) ($data['is_demo'] ?? false);
                return $this->normalize($data);
            }
            throw new RuntimeException('AI service returned HTTP '.$response->status().'.');
        } catch (\Throwable $e) {
            if (!config('services.ai.allow_demo_fallback')) {
                throw new RuntimeException('AI service is unavailable: '.$e->getMessage(), previous: $e);
            }
            return $this->demoPrediction($image);
        }
    }
    private function normalize(array $data): array
    {
        if (!isset($data['predicted_class'], $data['confidence'])) {
            throw new RuntimeException('AI service response is missing required fields.');
        }
        return [
            'predicted_class' => (string) $data['predicted_class'],
            'confidence' => max(0, min(1, (float) $data['confidence'])),
            'top_predictions' => array_slice($data['top_predictions'] ?? [], 0, 3),
            'model_version' => (string) ($data['model_version'] ?? 'unknown'),
            'is_demo' => (bool) ($data['is_demo'] ?? false),
            'raw' => $data,
        ];
    }
    private function demoPrediction(UploadedFile $image): array
    {
        $classes = config('plant_diseases.classes');
        $hash = hash_file('sha256', $image->getRealPath());
        $index = hexdec(substr($hash, 0, 4)) % count($classes);
        $ordered = [$classes[$index], $classes[($index + 5) % count($classes)], $classes[($index + 11) % count($classes)]];
        $scores = [0.82, 0.11, 0.07];
        return [
            'predicted_class' => $ordered[0],
            'confidence' => $scores[0],
            'top_predictions' => [
                ['class' => $ordered[0], 'confidence' => $scores[0]],
                ['class' => $ordered[1], 'confidence' => $scores[1]],
                ['class' => $ordered[2], 'confidence' => $scores[2]],
            ],
            'model_version' => 'DEMO-FALLBACK-NOT-A-REAL-MODEL',
            'is_demo' => true,
            'raw' => ['warning' => 'Development-only deterministic fallback. Do not report this as an AI result.'],
        ];
    }
    public function confidenceLabel(float $score): string
    {
        if ($score >= config('services.ai.high_confidence', .75)) return 'High confidence';
        if ($score >= config('services.ai.low_confidence', .50)) return 'Moderate confidence';
        return 'Low confidence — upload another clear image';
    }
}
