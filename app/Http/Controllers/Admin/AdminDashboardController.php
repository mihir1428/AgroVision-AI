<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Disease;
use App\Models\Feedback;
use App\Models\Prediction;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $completed = Prediction::where('status', 'completed');

        $high = (float) config('services.ai.high_confidence', 0.85);
        $low = (float) config('services.ai.low_confidence', 0.65);

        return view('admin.dashboard', [
            'users' => User::count(),
            'predictions' => Prediction::count(),
            'completedPredictions' => (clone $completed)->count(),
            'failedPredictions' => Prediction::where('status', 'failed')->count(),
            'pendingPredictions' => Prediction::whereIn('status', ['queued', 'processing'])->count(),
            'diseases' => Disease::where('is_active', true)->count(),
            'avgConfidence' => round((float) ((clone $completed)->avg('confidence') ?? 0) * 100, 1),
            'highConfidencePredictions' => (clone $completed)->where('confidence', '>=', $high)->count(),
            'moderateConfidencePredictions' => (clone $completed)->where('confidence', '>=', $low)->where('confidence', '<', $high)->count(),
            'lowConfidencePredictions' => (clone $completed)->where('confidence', '<', $low)->count(),
            'correctFeedback' => Feedback::where('is_correct', true)->count(),
            'incorrectFeedback' => Feedback::where('is_correct', false)->count(),
            'recent' => Prediction::with(['user','disease'])->latest()->limit(10)->get(),
        ]);
    }
}
