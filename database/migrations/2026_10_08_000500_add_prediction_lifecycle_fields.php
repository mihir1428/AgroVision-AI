<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->string('confidence_label', 80)->nullable()->after('confidence');
            $table->text('failure_reason')->nullable()->after('status');
            $table->timestamp('started_at')->nullable()->after('failure_reason');
            $table->timestamp('completed_at')->nullable()->after('started_at');
            $table->index(['status', 'created_at']);
        });

        DB::table('predictions')
            ->orderBy('id')
            ->get()
            ->each(function ($row): void {
                $statusText = strtolower((string) ($row->status ?? ''));
                $predicted = (string) ($row->predicted_class ?? '');
                $confidence = (float) ($row->confidence ?? 0);

                if (str_contains($statusText, 'fail')) {
                    $status = 'failed';
                } elseif ($predicted !== '' && $predicted !== 'Pending' && $confidence > 0) {
                    $status = 'completed';
                } elseif (str_contains($statusText, 'process')) {
                    $status = 'processing';
                } else {
                    $status = 'queued';
                }

                $label = null;
                if ($status === 'completed') {
                    $label = $confidence >= .75
                        ? 'High confidence'
                        : ($confidence >= .50 ? 'Moderate confidence' : 'Low confidence');
                }

                DB::table('predictions')->where('id', $row->id)->update([
                    'status' => $status,
                    'confidence_label' => $label,
                    'started_at' => in_array($status, ['processing', 'completed', 'failed'], true) ? ($row->updated_at ?? now()) : null,
                    'completed_at' => in_array($status, ['completed', 'failed'], true) ? ($row->updated_at ?? now()) : null,
                ]);
            });
    }

    public function down(): void
    {
        Schema::table('predictions', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropColumn([
                'confidence_label',
                'failure_reason',
                'started_at',
                'completed_at',
            ]);
        });
    }
};
