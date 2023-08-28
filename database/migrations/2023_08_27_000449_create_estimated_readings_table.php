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
        Schema::create('estimated_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meters_id')->nullable();
            $table->double('estimated_reading', 15, 8);
            $table->foreign('meters_id')->references('id')->on('meters')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estimated_readings');
    }
};
