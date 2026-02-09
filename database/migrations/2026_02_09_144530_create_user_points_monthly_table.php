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
        Schema::create('user_points_monthly', function (Blueprint $table) {
            $table->uuid('user_id')->primary();
            $table->string('month');
            $table->unsignedBigInteger('points')->default(0);
        
            $table->unique(['user_id', 'month']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_points_monthly');
    }
};
