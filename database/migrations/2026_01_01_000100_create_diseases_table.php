<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('diseases', function (Blueprint $table) { $table->id(); $table->string('class_key')->unique(); $table->string('crop_name'); $table->string('disease_name'); $table->string('scientific_name')->nullable(); $table->text('description'); $table->text('symptoms'); $table->text('cause')->nullable(); $table->text('prevention'); $table->text('management'); $table->text('bangla_description')->nullable(); $table->text('bangla_symptoms')->nullable(); $table->text('bangla_management')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps(); $table->index(['crop_name','is_active']); }); }
    public function down(): void { Schema::dropIfExists('diseases'); }
};
