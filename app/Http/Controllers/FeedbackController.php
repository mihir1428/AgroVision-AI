<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Prediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function store(Request $request, Prediction $prediction): RedirectResponse
    {
        abort_unless($prediction->user_id === $request->user()->id, 403);
        abort_unless($prediction->isCompleted(), 422, 'Feedback is available after the AI analysis is completed.');

        $data = $request->validate([
            'is_correct' => ['required', 'boolean'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        Feedback::updateOrCreate(
            ['prediction_id' => $prediction->id, 'user_id' => $request->user()->id],
            ['is_correct' => $data['is_correct'], 'comment' => $data['comment'] ?? null]
        );

        return back()->with('success', 'Thank you. Your feedback was saved.');
    }
}
