<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('predictions', function (Blueprint $table) { $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->foreignId('disease_id')->nullable()->constrained()->nullOnDelete(); $table->string('image_path'); $table->string('predicted_class'); $table->decimal('confidence',6,5); $table->json('top_predictions')->nullable(); $table->string('model_version')->nullable(); $table->boolean('is_demo')->default(false); $table->string('status',100)->nullable(); $table->json('raw_response')->nullable(); $table->timestamps(); $table->index(['user_id','created_at']); }); }
    public function down(): void { Schema::dropIfExists('predictions'); }
};
