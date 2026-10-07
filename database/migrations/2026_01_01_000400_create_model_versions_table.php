<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('model_versions', function (Blueprint $table) { $table->id(); $table->string('version')->unique(); $table->string('architecture'); $table->decimal('accuracy',6,5)->nullable(); $table->decimal('precision',6,5)->nullable(); $table->decimal('recall',6,5)->nullable(); $table->decimal('f1_score',6,5)->nullable(); $table->text('notes')->nullable(); $table->timestamp('deployed_at')->nullable(); $table->boolean('is_active')->default(false); $table->timestamps(); }); }
    public function down(): void { Schema::dropIfExists('model_versions'); }
};
