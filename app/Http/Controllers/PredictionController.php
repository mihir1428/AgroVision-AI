<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPlantPrediction;
use App\Models\Prediction;
use Illuminate\Http\JsonResponse;
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
        $path = $file->store('leaf-scans/'.now()->format('Y/m'), 'public');

        $prediction = Prediction::create([
            'user_id' => $request->user()->id,
            'disease_id' => null,
            'image_path' => $path,
            'predicted_class' => 'Pending',
            'confidence' => 0,
            'confidence_label' => null,
            'top_predictions' => [],
            'model_version' => null,
            'is_demo' => false,
            'status' => 'queued',
            'failure_reason' => null,
            'started_at' => null,
            'completed_at' => null,
            'raw_response' => null,
        ]);

        ProcessPlantPrediction::dispatch($prediction->id);

        return redirect()->route('predictions.show', $prediction);
    }

    public function index(Request $request): View
    {
        $predictions = $request->user()
            ->predictions()
            ->with(['disease', 'feedback'])
            ->latest()
            ->paginate(10);

        return view('predictions.index', compact('predictions'));
    }

    public function show(Request $request, Prediction $prediction): View
    {
        $this->authorizeView($request, $prediction);
        $prediction->load(['disease', 'feedback']);

        return view('predictions.show', compact('prediction'));
    }

    public function status(Request $request, Prediction $prediction): JsonResponse
    {
        $this->authorizeView($request, $prediction);
        $prediction->load('disease');

        return response()->json([
            'id' => $prediction->id,
            'status' => $prediction->status,
            'status_label' => $prediction->statusLabel(),
            'terminal' => $prediction->isTerminal(),
            'confidence' => $prediction->confidence,
            'confidence_label' => $prediction->confidence_label,
            'predicted_class' => $prediction->predicted_class,
            'disease' => $prediction->disease?->only(['crop_name', 'disease_name']),
            'updated_at' => $prediction->updated_at?->toIso8601String(),
        ]);
    }

    public function retry(Request $request, Prediction $prediction): RedirectResponse
    {
        abort_unless($prediction->user_id === $request->user()->id, 403);
        abort_unless($prediction->isFailed(), 422, 'Only failed predictions can be retried.');

        $prediction->update([
            'disease_id' => null,
            'predicted_class' => 'Pending',
            'confidence' => 0,
            'confidence_label' => null,
            'top_predictions' => [],
            'model_version' => null,
            'is_demo' => false,
            'status' => 'queued',
            'failure_reason' => null,
            'started_at' => null,
            'completed_at' => null,
            'raw_response' => null,
        ]);

        ProcessPlantPrediction::dispatch($prediction->id);

        return redirect()->route('predictions.show', $prediction)
            ->with('success', 'The image was queued for another AI analysis.');
    }

    public function destroy(Request $request, Prediction $prediction): RedirectResponse
    {
        abort_unless($prediction->user_id === $request->user()->id, 403);

        if ($prediction->image_path) {
            Storage::disk('public')->delete($prediction->image_path);
        }

        $prediction->delete();

        return redirect()->route('predictions.index')->with('success', 'Scan deleted.');
    }

    private function authorizeView(Request $request, Prediction $prediction): void
    {
        abort_unless(
            $prediction->user_id === $request->user()->id || $request->user()->is_admin,
            403
        );
    }
}
