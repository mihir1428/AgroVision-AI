<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('predictions')
            ->where('status', 'completed')
            ->where('confidence', '>=', 0.85)
            ->update(['confidence_label' => 'High confidence']);

        DB::table('predictions')
            ->where('status', 'completed')
            ->where('confidence', '>=', 0.65)
            ->where('confidence', '<', 0.85)
            ->update(['confidence_label' => 'Moderate confidence']);

        DB::table('predictions')
            ->where('status', 'completed')
            ->where('confidence', '<', 0.65)
            ->update(['confidence_label' => 'Low confidence — upload another clear image']);
    }

    public function down(): void
    {
        // Labels are descriptive data. We intentionally do not guess previous values.
    }
};
