@extends('layouts.app')
@section('title','Admin Dashboard')
@section('content')
<section class="section">
    <div class="container">
        <div class="page-head">
            <div><span class="eyebrow">ADMINISTRATION</span><h1>System dashboard</h1></div>
            <div class="actions">
                <a class="btn secondary" href="{{ route('admin.diseases.index') }}">Disease knowledge base</a>
                <a class="btn secondary" href="{{ route('admin.predictions.index') }}">All predictions</a>
            </div>
        </div>

        <div class="stats admin-stats">
            <div class="stat"><span>Users</span><strong>{{ $users }}</strong></div>
            <div class="stat"><span>Total predictions</span><strong>{{ $predictions }}</strong></div>
            <div class="stat"><span>Completed</span><strong>{{ $completedPredictions }}</strong></div>
            <div class="stat"><span>Pending</span><strong>{{ $pendingPredictions }}</strong></div>
            <div class="stat"><span>Failed</span><strong>{{ $failedPredictions }}</strong></div>
            <div class="stat"><span>Active classes</span><strong>{{ $diseases }}</strong></div>
            <div class="stat"><span>Avg confidence</span><strong>{{ $avgConfidence }}%</strong></div>
        </div>

        <div class="panel">
            <h2>Confidence distribution</h2>
            <p>High (85%+): <strong>{{ $highConfidencePredictions }}</strong> · Moderate (65–84.9%): <strong>{{ $moderateConfidencePredictions }}</strong> · Low (&lt;65%): <strong>{{ $lowConfidencePredictions }}</strong></p>
        </div>

        <div class="panel top-space">
            <h2>User feedback</h2>
            <p>Looks correct: <strong>{{ $correctFeedback }}</strong> · Looks incorrect: <strong>{{ $incorrectFeedback }}</strong></p>
        </div>

        <div class="panel top-space">
            <h2>Latest predictions</h2>
            @forelse($recent as $p)
                <div class="history-row">
                    <div>
                        <strong>{{ $p->user->email }} — {{ $p->disease?->disease_name ?? ($p->isCompleted() ? $p->predicted_class : $p->statusLabel()) }}</strong>
                        <span>{{ $p->created_at->format('d M Y H:i') }} · {{ $p->statusLabel() }}</span>
                    </div>
                    <b>{{ $p->isCompleted() ? number_format($p->confidence*100,1).'%' : '—' }}</b>
                </div>
            @empty
                <p>No predictions yet.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
