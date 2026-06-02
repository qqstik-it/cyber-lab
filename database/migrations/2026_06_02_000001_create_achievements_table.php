<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('icon');
            $table->unsignedInteger('threshold');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['topic_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
