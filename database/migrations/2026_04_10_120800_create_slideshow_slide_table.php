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
        Schema::create('slideshow_slide', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slide_id')->constrained()->cascadeOnDelete();
            $table->foreignId('slideshow_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->unique(['slide_id', 'slideshow_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slideshow_slide');
    }
};

