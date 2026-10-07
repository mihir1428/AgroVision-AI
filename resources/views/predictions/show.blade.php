@extends('layouts.app')

@section('title', 'Prediction Result')

@section('content')

<section class="section">
    <div class="container">

        <div class="page-head">
            <div>
                <span class="eyebrow">ANALYSIS RESULT</span>

                <h1>
                    {{ $prediction->disease?->crop_name ?? 'Supported class' }}
                    ·
                    {{ $prediction->disease?->disease_name ?? $prediction->predicted_class }}
                </h1>

                <p>{{ $prediction->status }}</p>
            </div>

            <a
                class="btn secondary"
                href="{{ route('predictions.create') }}"
            >
                Scan another
            </a>
        </div>


        {{-- Demo warning --}}
        @if($prediction->is_demo)
            <div class="alert warning">
                <strong>Development demo result:</strong>

                this prediction came from the deterministic fallback,
                not a trained CNN.

                Start the included AI service and set

                <code>AI_ALLOW_DEMO_FALLBACK=false</code>

                before evaluation or presentation.
            </div>
        @endif


        <div class="result-grid">

            {{-- LEFT SIDE --}}
            <div>

                <div class="image-card">
                    <img
                        src="{{ Storage::url($prediction->image_path) }}"
                        alt="Uploaded leaf"
                    >
                </div>


                <div class="panel top-space">

                    <h3>Top predictions</h3>

                    @forelse($prediction->top_predictions ?? [] as $candidate)

                        <div class="score-row">

                            <span>
                                {{
                                    str_replace(
                                        ['___', '_'],
                                        [' · ', ' '],
                                        $candidate['class'] ?? 'Unknown'
                                    )
                                }}
                            </span>

                            <b>
                                {{
                                    number_format(
                                        ($candidate['confidence'] ?? 0) * 100,
                                        1
                                    )
                                }}%
                            </b>

                        </div>

                    @empty

                        <p>
                            AI analysis is still processing.
                        </p>

                    @endforelse

                </div>

            </div>


            {{-- RIGHT SIDE --}}
            <div>

                <div class="confidence-card">

                    <span>Prediction confidence</span>

                    <strong>
                        {{
                            number_format(
                                $prediction->confidence * 100,
                                1
                            )
                        }}%
                    </strong>

                    <div class="bar">
                        <i
                            style="
                                width:
                                {{
                                    min(
                                        100,
                                        $prediction->confidence * 100
                                    )
                                }}%
                            "
                        ></i>
                    </div>

                    <small>
                        Model:
                        {{ $prediction->model_version ?: 'unknown' }}
                    </small>

                </div>


                {{-- Disease information --}}
                @if($prediction->disease)

                    <div class="panel top-space">

                        <h2>About this result</h2>

                        <h3>Description</h3>

                        <p>
                            {{ $prediction->disease->description }}
                        </p>


                        <h3>Common symptoms</h3>

                        <p>
                            {{ $prediction->disease->symptoms }}
                        </p>


                        <h3>Prevention</h3>

                        <p>
                            {{ $prediction->disease->prevention }}
                        </p>


                        <h3>General management</h3>

                        <p>
                            {{ $prediction->disease->management }}
                        </p>

                    </div>

                @else

                    <div class="alert warning top-space">

                        @if(
                            $prediction->predicted_class === 'Pending'
                            || str_contains(
                                strtolower($prediction->status ?? ''),
                                'queued'
                            )
                            || str_contains(
                                strtolower($prediction->status ?? ''),
                                'processing'
                            )
                        )

                            AI analysis is currently processing.
                            Please wait a few seconds.

                        @else

                            No active knowledge-base record matches
                            this model class.

                            Ask an administrator to map

                            <code>
                                {{ $prediction->predicted_class }}
                            </code>.

                        @endif

                    </div>

                @endif


                {{-- Feedback --}}
                <div class="panel top-space">

                    <h3>
                        Was this prediction useful?
                    </h3>

                    <form
                        method="POST"
                        action="{{ route('feedback.store', $prediction) }}"
                    >

                        @csrf

                        <div class="radio-row">

                            <label>

                                <input
                                    type="radio"
                                    name="is_correct"
                                    value="1"

                                    {{
                                        $prediction->feedback?->is_correct === true
                                        ? 'checked'
                                        : ''
                                    }}
                                >

                                Looks correct

                            </label>


                            <label>

                                <input
                                    type="radio"
                                    name="is_correct"
                                    value="0"

                                    {{
                                        $prediction->feedback
                                        && !$prediction->feedback->is_correct
                                        ? 'checked'
                                        : ''
                                    }}
                                >

                                Looks incorrect

                            </label>

                        </div>


                        <label>

                            Optional comment

                            <textarea
                                name="comment"
                                rows="3"
                            >{{ $prediction->feedback?->comment }}</textarea>

                        </label>


                        <button class="btn btn-sm">
                            Save feedback
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</section>


{{-- Socket.IO Client --}}
<script
    src="http://127.0.0.1:3001/socket.io/socket.io.js"
></script>


<script>
document.addEventListener(
    'DOMContentLoaded',
    function () {

        const predictionId =
            @json($prediction->id);

        const currentStatus =
            @json($prediction->status);

        const socket = io(
            'http://127.0.0.1:3001'
        );


        socket.on(
            'connect',
            function () {

                console.log(
                    'Connected to AgroVision realtime service'
                );

                socket.emit(
                    'join-prediction',
                    predictionId
                );
            }
        );


        socket.on(
            'prediction-updated',
            function (data) {

                console.log(
                    'Prediction update:',
                    data
                );


                if (
                    Number(data.prediction_id)
                    !==
                    Number(predictionId)
                ) {
                    return;
                }


                if (
                    data.status === 'completed'
                    ||
                    data.status === 'failed'
                ) {

                    window.location.reload();

                }

            }
        );


        socket.on(
            'connect_error',
            function (error) {

                console.error(
                    'Realtime connection error:',
                    error
                );

            }
        );

    }
);
</script>

@endsection