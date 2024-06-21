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
        Schema::create('eng_rates', function (Blueprint $table) {
            $table->id();
            $table->float('meeting_rate')->default(10);
            $table->float('overall_rating')->default(0);
            $table->foreignId('eng_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('eng_rates');
    }
};
