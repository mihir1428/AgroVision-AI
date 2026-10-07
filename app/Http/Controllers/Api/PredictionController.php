<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Services\PlantDiseaseAiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PredictionController extends Controller
{
    public function __invoke(Request $request, PlantDiseaseAiService $ai): JsonResponse
    {
        $request->validate([
            'leaf_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        $result = $ai->predict($request->file('leaf_image'));
        $disease = Disease::where('class_key', $result['predicted_class'])
            ->where('is_active', true)
            ->first();

        return response()->json([
            'prediction' => [
                'class' => $result['predicted_class'],
                'confidence' => $result['confidence'],
                'confidence_label' => $ai->confidenceLabel($result['confidence']),
                'top_predictions' => $result['top_predictions'],
                'model_version' => $result['model_version'],
                'is_demo' => $result['is_demo'],
            ],
            'disease' => $disease?->only([
                'crop_name',
                'disease_name',
                'scientific_name',
                'description',
                'symptoms',
                'cause',
                'prevention',
                'management',
            ]),
        ]);
    }
}
