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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('image');
            $table->integer('progress_current')->default(0);
            $table->integer('progress_total')->default(0);
            $table->timestamps();
        });

        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('subtitle');
            $table->timestamps();
        });

        Schema::create('user_stats', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('progress');
            $table->string('color');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('levels');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('user_stats');
    }
};
