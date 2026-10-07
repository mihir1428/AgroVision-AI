<?php

namespace App\Notifications;

use App\Models\Prediction;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PredictionStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Prediction $prediction,
        public string $eventStatus,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return $this->payload();
    }

    public function payload(): array
    {
        $prediction = $this->prediction;
        $prediction->loadMissing('disease');

        $name = $prediction->disease
            ? $prediction->disease->crop_name.' · '.$prediction->disease->disease_name
            : str_replace(['___', '_'], [' · ', ' '], (string) $prediction->predicted_class);

        if ($this->eventStatus === 'completed') {
            $label = $prediction->confidence_label ?: 'Analysis completed';
            $message = sprintf('%s — %.1f%% (%s)', $name, $prediction->confidence * 100, strtolower($label));

            if ($prediction->confidence < (float) config('services.ai.low_confidence', 0.65)) {
                $message .= ' · Try another clear image before acting on the result.';
            }

            return [
                'type' => 'prediction_completed',
                'title' => 'Leaf analysis completed',
                'message' => $message,
                'prediction_id' => $prediction->id,
                'status' => 'completed',
                'confidence' => $prediction->confidence,
                'confidence_label' => $label,
                'url' => '/predictions/'.$prediction->id,
            ];
        }

        return [
            'type' => 'prediction_failed',
            'title' => 'Leaf analysis needs attention',
            'message' => 'The AI analysis could not be completed. You can retry the same image.',
            'prediction_id' => $prediction->id,
            'status' => 'failed',
            'confidence' => null,
            'url' => '/predictions/'.$prediction->id,
        ];
    }
}
