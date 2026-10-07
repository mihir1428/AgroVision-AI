@extends('layouts.app')
@section('title','Dashboard')
@section('content')
<section class="section">
    <div class="container">
        <div class="page-head">
            <div>
                <span class="eyebrow">YOUR WORKSPACE</span>
                <h1>Hello, {{ auth()->user()->name }}</h1>
                <p>Upload a leaf photo or review your previous AI screenings.</p>
            </div>
            <a class="btn" href="{{ route('predictions.create') }}">+ New scan</a>
        </div>

        <div class="stats">
            <div class="stat"><span>Total scans</span><strong>{{ $totalScans }}</strong></div>
            <div class="stat"><span>Completed</span><strong>{{ $completedScans }}</strong></div>
            <div class="stat"><span>Average confidence</span><strong>{{ $avgConfidence }}%</strong></div>
            <div class="stat"><span>Supported classes</span><strong>18</strong></div>
        </div>

        <div class="panel">
            <div class="panel-head"><h2>Recent scans</h2><a href="{{ route('predictions.index') }}">View all</a></div>
            @forelse($recent as $item)
                <a class="history-row" href="{{ route('predictions.show',$item) }}">
                    <div>
                        <strong>
                            @if($item->isCompleted())
                                {{ $item->disease?->crop_name ?? 'Supported class' }} · {{ $item->disease?->disease_name ?? $item->predicted_class }}
                            @elseif($item->isFailed())
                                Analysis failed
                            @else
                                Analysis {{ $item->status }}
                            @endif
                        </strong>
                        <span>{{ $item->created_at->format('d M Y, h:i A') }} · {{ $item->statusLabel() }}</span>
                    </div>
                    <b>{{ $item->isCompleted() ? number_format($item->confidence*100,1).'%' : '—' }}</b>
                </a>
            @empty
                <p>No scans yet. Upload your first leaf image.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
