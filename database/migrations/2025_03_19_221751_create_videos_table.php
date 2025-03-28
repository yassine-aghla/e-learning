<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable(); 
            $table->string('url'); 
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); 
            $table->timestamps(); 
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}