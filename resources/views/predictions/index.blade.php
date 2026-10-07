@extends('layouts.app')
@section('title','Scan History')
@section('content')
<section class="section">
    <div class="container">
        <div class="page-head">
            <div><span class="eyebrow">SCAN HISTORY</span><h1>Your previous screenings</h1></div>
            <a class="btn" href="{{ route('predictions.create') }}">New scan</a>
        </div>

        <div class="panel table-wrap">
            <table>
                <thead><tr><th>Date</th><th>Result</th><th>Status</th><th>Confidence</th><th>Mode</th><th></th></tr></thead>
                <tbody>
                @forelse($predictions as $p)
                    <tr>
                        <td>{{ $p->created_at->format('d M Y H:i') }}</td>
                        <td>
                            @if($p->isCompleted())
                                {{ $p->disease?->crop_name ?? 'Supported class' }} · {{ $p->disease?->disease_name ?? $p->predicted_class }}
                            @elseif($p->isFailed())
                                Analysis failed
                            @else
                                Waiting for AI result
                            @endif
                        </td>
                        <td><span class="badge status-{{ $p->status }}">{{ $p->statusLabel() }}</span></td>
                        <td>{{ $p->isCompleted() ? number_format($p->confidence*100,1).'%' : '—' }}</td>
                        <td>@if($p->is_demo)<span class="badge orange">Demo</span>@else<span class="badge">AI</span>@endif</td>
                        <td class="actions-cell">
                            <a href="{{ route('predictions.show',$p) }}">Open</a>
                            <form method="POST" action="{{ route('predictions.destroy',$p) }}" class="inline" onsubmit="return confirm('Delete this scan?')">
                                @csrf @method('DELETE')
                                <button class="link-button danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No scans found.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $predictions->links() }}
        </div>
    </div>
</section>
@endsection
