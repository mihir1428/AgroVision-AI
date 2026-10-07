<?php
namespace App\Http\Controllers;
use Illuminate\View\View;
class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();
        $recent = $user->predictions()->with('disease')->latest()->limit(5)->get();
        return view('dashboard', [
            'recent' => $recent,
            'totalScans' => $user->predictions()->count(),
            'avgConfidence' => round((float) ($user->predictions()->avg('confidence') ?? 0) * 100, 1),
        ]);
    }
}
