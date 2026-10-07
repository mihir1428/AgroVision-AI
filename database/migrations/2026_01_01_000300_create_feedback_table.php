<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void { Schema::create('feedback', function (Blueprint $table) { $table->id(); $table->foreignId('prediction_id')->constrained()->cascadeOnDelete(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->boolean('is_correct'); $table->string('comment',500)->nullable(); $table->timestamps(); $table->unique(['prediction_id','user_id']); }); }
    public function down(): void { Schema::dropIfExists('feedback'); }
};
