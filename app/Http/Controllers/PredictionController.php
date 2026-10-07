<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPlantPrediction;
use App\Models\Prediction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PredictionController extends Controller
{
    public function create(): View
    {
        return view('predictions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'leaf_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:8192',
                'dimensions:min_width=100,min_height=100',
            ],
        ]);

        $file = $request->file('leaf_image');

        $path = $file->store(
            'leaf-scans/' . now()->format('Y/m'),
            'public'
        );

        $prediction = Prediction::create([
            'user_id' => $request->user()->id,
            'disease_id' => null,
            'image_path' => $path,

            // Temporary values until Redis worker finishes
            'predicted_class' => 'Pending',
            'confidence' => 0,
            'top_predictions' => [],

            'model_version' => null,
            'is_demo' => false,
            'status' => 'Queued for AI analysis',
            'raw_response' => null,
        ]);

        ProcessPlantPrediction::dispatch($prediction->id);

        return redirect()
            ->route('predictions.show', $prediction);
    }

    public function index(Request $request): View
    {
        $predictions = $request->user()
            ->predictions()
            ->with(['disease', 'feedback'])
            ->latest()
            ->paginate(10);

        return view(
            'predictions.index',
            compact('predictions')
        );
    }

    public function show(
        Request $request,
        Prediction $prediction
    ): View {
        abort_unless(
            $prediction->user_id === $request->user()->id
            || $request->user()->is_admin,
            403
        );

        $prediction->load([
            'disease',
            'feedback'
        ]);

        return view(
            'predictions.show',
            compact('prediction')
        );
    }

    public function destroy(
        Request $request,
        Prediction $prediction
    ): RedirectResponse {
        abort_unless(
            $prediction->user_id === $request->user()->id,
            403
        );

        if ($prediction->image_path) {
            Storage::disk('public')
                ->delete($prediction->image_path);
        }

        $prediction->delete();

        return redirect()
            ->route('predictions.index')
            ->with('success', 'Scan deleted.');
    }
}