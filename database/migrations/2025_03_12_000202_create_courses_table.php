<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('title');
            $table->string('description');
            $table->string('content');
            $table->string('video')->nullable();
            $table->string('cover')->nullable();
            $table->decimal('duration', 8, 2)->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced']);
            // $table->foreignUuid('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->foreignid('category_id')->constrained('categories')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
