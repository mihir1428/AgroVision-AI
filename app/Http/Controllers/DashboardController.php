<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        return view('dashboard', [
            'recent' => $user->predictions()->with('disease')->latest()->limit(5)->get(),
            'totalScans' => $user->predictions()->count(),
            'completedScans' => $user->predictions()->where('status', 'completed')->count(),
            'avgConfidence' => round(
                (float) ($user->predictions()->where('status', 'completed')->avg('confidence') ?? 0) * 100,
                1
            ),
        ]);
    }
}
