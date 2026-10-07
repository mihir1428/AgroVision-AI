@extends('layouts.app')

@section('title', 'Prediction Result')

@section('content')
@php
    $isQueued = $prediction->isQueued();
    $isProcessing = $prediction->isProcessing();
    $isPending = $isQueued || $isProcessing;
    $isCompleted = $prediction->isCompleted();
    $isFailed = $prediction->isFailed();
    $highThreshold = (float) config('services.ai.high_confidence', .85);
    $lowThreshold = (float) config('services.ai.low_confidence', .65);
    $ambiguityMargin = (float) config('services.ai.ambiguity_margin', .15);
    $rankedPredictions = collect($prediction->top_predictions ?? []);
    $secondConfidence = (float) data_get($rankedPredictions->get(1), 'confidence', 0);
    $topGap = $isCompleted ? max(0, (float) $prediction->confidence - $secondConfidence) : null;
    $isLowConfidence = $isCompleted && $prediction->confidence < $lowThreshold;
    $isModerateConfidence = $isCompleted && $prediction->confidence >= $lowThreshold && $prediction->confidence < $highThreshold;
    $hasCloseAlternative = $isCompleted && $rankedPredictions->count() > 1 && $topGap < $ambiguityMargin;
@endphp

<section class="section">
    <div class="container">
        <div class="page-head">
            <div>
                <span class="eyebrow">ANALYSIS RESULT</span>

                @if($isCompleted)
                    <h1>{{ $isLowConfidence ? 'Possible match · ' : '' }}{{ $prediction->disease?->crop_name ?? 'Supported class' }} · {{ $prediction->disease?->disease_name ?? str_replace(['___','_'], [' · ',' '], $prediction->predicted_class) }}</h1>
                @elseif($isFailed)
                    <h1>Analysis could not be completed</h1>
                @else
                    <h1>{{ $isProcessing ? 'Analyzing your leaf image' : 'Leaf image queued' }}</h1>
                @endif

                <p id="predictionStatusText">{{ $prediction->statusLabel() }}</p>
            </div>

            <a class="btn secondary" href="{{ route('predictions.create') }}">Scan another</a>
        </div>

        @if($prediction->is_demo)
            <div class="alert warning">
                <strong>Development demo result:</strong> this prediction came from the deterministic fallback, not the trained CNN.
                Start the included AI service and set <code>AI_ALLOW_DEMO_FALLBACK=false</code> before evaluation or presentation.
            </div>
        @endif

        @if($isLowConfidence)
            <div class="alert warning">
                <strong>Low-confidence result.</strong> Treat this as a possible match, not a diagnosis. Take another clear photo of one leaf in good light and compare the result with visible field symptoms before taking action.
            </div>
        @elseif($isModerateConfidence)
            <div class="alert info">
                <strong>Moderate-confidence result.</strong> The model found a reasonable match, but field symptoms and the alternative predictions should still be checked.
            </div>
        @endif

        @if($hasCloseAlternative)
            <div class="alert warning">
                <strong>Close alternative detected.</strong> The top two predictions are only {{ number_format($topGap * 100, 1) }} percentage points apart. Consider another image or expert confirmation if the visible symptoms are unclear.
            </div>
        @endif

        @if($isFailed)
            <div class="alert error">
                <strong>The AI service could not finish this analysis.</strong>
                The image is still saved, so you can retry without uploading it again.
                @if(config('app.debug') && $prediction->failure_reason)
                    <div class="small top-space-sm"><code>{{ $prediction->failure_reason }}</code></div>
                @endif
            </div>
            <form method="POST" action="{{ route('predictions.retry', $prediction) }}" class="retry-form">
                @csrf
                <button class="btn">Retry analysis</button>
            </form>
        @endif

        <div class="result-grid">
            <div>
                <div class="image-card">
                    <img src="{{ Storage::url($prediction->image_path) }}" alt="Uploaded leaf">
                </div>

                <div class="panel top-space">
                    <h3>Top predictions</h3>

                    @if($isCompleted)
                        @forelse($prediction->top_predictions ?? [] as $candidate)
                            <div class="score-row">
                                <span>{{ str_replace(['___', '_'], [' · ', ' '], $candidate['class'] ?? 'Unknown') }}</span>
                                <b>{{ number_format(($candidate['confidence'] ?? 0) * 100, 1) }}%</b>
                            </div>
                        @empty
                            <p>No ranked alternatives were returned by the AI service.</p>
                        @endforelse
                    @elseif($isFailed)
                        <p>Top predictions are unavailable because the analysis failed.</p>
                    @else
                        <div class="processing-row">
                            <span class="spinner" aria-hidden="true"></span>
                            <span>{{ $isProcessing ? 'The model is analyzing the image.' : 'Waiting for the queue worker.' }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                <div class="confidence-card {{ $isPending ? 'processing-card' : '' }}">
                    @if($isCompleted)
                        <span>Prediction confidence</span>
                        <strong>{{ number_format($prediction->confidence * 100, 1) }}%</strong>
                        <div class="bar"><i style="width: {{ min(100, $prediction->confidence * 100) }}%"></i></div>
                        <small>{{ $prediction->confidence_label }} · Model: {{ $prediction->model_version ?: 'unknown' }}</small>
                        @if($rankedPredictions->count() > 1)
                            <small class="confidence-detail">Top-2 separation: {{ number_format($topGap * 100, 1) }} percentage points</small>
                        @endif
                    @elseif($isFailed)
                        <span>Prediction confidence</span>
                        <strong>—</strong>
                        <small>No confidence score is available for a failed analysis.</small>
                    @else
                        <span>AI pipeline status</span>
                        <strong class="processing-word">{{ $isProcessing ? 'Processing' : 'Queued' }}</strong>
                        <div class="bar indeterminate"><i></i></div>
                        <small>The page will update automatically when the result is ready.</small>
                    @endif
                </div>

                @if($isCompleted && $prediction->disease)
                    <div class="panel top-space">
                        <h2>About this result</h2>

                        @if($prediction->disease->scientific_name)
                            <p class="scientific-name"><em>{{ $prediction->disease->scientific_name }}</em></p>
                        @endif

                        <h3>Description</h3>
                        <p>{{ $prediction->disease->description }}</p>

                        <h3>Common symptoms</h3>
                        <p>{{ $prediction->disease->symptoms }}</p>

                        <h3>Likely cause</h3>
                        <p>{{ $prediction->disease->cause ?: 'Cause information is not available for this class.' }}</p>

                        <h3>Prevention</h3>
                        <p>{{ $prediction->disease->prevention }}</p>

                        <h3>Recommended actions</h3>
                        <p>{{ $prediction->disease->management }}</p>

                        <div class="notice">
                            This is an AI-based preliminary screening result. It does not replace field inspection, laboratory diagnosis, or advice from a qualified agricultural professional.
                        </div>
                    </div>
                @elseif($isCompleted)
                    <div class="alert warning top-space">
                        No active knowledge-base record matches <code>{{ $prediction->predicted_class }}</code>. Ask an administrator to map this model class.
                    </div>
                @elseif($isPending)
                    <div class="alert warning top-space" id="processingNotice">
                        {{ $isProcessing ? 'AI analysis is currently processing.' : 'The scan is waiting in the processing queue.' }} Please keep this page open; realtime updates and a polling fallback are active.
                    </div>
                @endif

                @if($isCompleted)
                    <div class="panel top-space">
                        <h3>Was this prediction useful?</h3>
                        <form method="POST" action="{{ route('feedback.store', $prediction) }}">
                            @csrf
                            <div class="radio-row">
                                <label>
                                    <input type="radio" name="is_correct" value="1" {{ $prediction->feedback?->is_correct === true ? 'checked' : '' }}>
                                    Looks correct
                                </label>
                                <label>
                                    <input type="radio" name="is_correct" value="0" {{ $prediction->feedback && !$prediction->feedback->is_correct ? 'checked' : '' }}>
                                    Looks incorrect
                                </label>
                            </div>

                            <label>
                                Optional comment
                                <textarea name="comment" rows="3">{{ $prediction->feedback?->comment }}</textarea>
                            </label>

                            <button class="btn btn-sm">Save feedback</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@if($isPending)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const predictionId = @json($prediction->id);
    const statusUrl = @json(route('predictions.status', $prediction));
    let finished = false;
    let pollTimer = null;

    function finish() {
        if (finished) return;
        finished = true;
        if (pollTimer) clearInterval(pollTimer);
        window.location.reload();
    }

    function handleStatus(status) {
        if (status === 'completed' || status === 'failed') finish();
    }

    const socket = window.agrovisionSocket;
    if (socket) {
        const join = () => socket.emit('join-prediction', predictionId);
        if (socket.connected) join();
        socket.on('connect', join);
        socket.on('prediction-updated', function (data) {
            if (Number(data?.prediction_id) !== Number(predictionId)) return;
            handleStatus(data.status);
        });
    }

    async function pollStatus() {
        try {
            const response = await fetch(statusUrl, { headers: { 'Accept': 'application/json' } });
            if (!response.ok) return;
            const data = await response.json();
            handleStatus(data.status);

            const statusText = document.getElementById('predictionStatusText');
            if (statusText && data.status_label) statusText.textContent = data.status_label;
        } catch (error) {
            console.debug('Prediction status poll skipped.', error);
        }
    }

    pollTimer = setInterval(pollStatus, 4000);
    setTimeout(pollStatus, 1500);
});
</script>
@endpush
@endif
