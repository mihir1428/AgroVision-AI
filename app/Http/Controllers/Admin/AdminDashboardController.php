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
        $recent = Prediction::with(['user','disease'])->latest()->limit(10)->get();
        return view('admin.dashboard', [
            'users' => User::count(), 'predictions' => Prediction::count(), 'diseases' => Disease::where('is_active', true)->count(),
            'avgConfidence' => round((float)(Prediction::avg('confidence') ?? 0)*100,1),
            'correctFeedback' => Feedback::where('is_correct', true)->count(), 'incorrectFeedback' => Feedback::where('is_correct', false)->count(),
            'recent' => $recent,
        ]);
    }
}
