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
        Schema::create('body_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('neck', 5, 2)->nullable();
            $table->decimal('shoulders', 5, 2)->nullable();
            $table->decimal('arm_relaxed', 5, 2)->nullable();
            $table->decimal('arm_flexed', 5, 2)->nullable();
            $table->decimal('forearm', 5, 2)->nullable();
            $table->decimal('wrist', 5, 2)->nullable();
            $table->decimal('chest', 5, 2)->nullable();
            $table->decimal('stomach', 5, 2)->nullable();
            $table->decimal('waist', 5, 2)->nullable();
            $table->decimal('hip', 5, 2)->nullable();
            $table->decimal('thigh', 5, 2)->nullable();
            $table->decimal('calf', 5, 2)->nullable();
            $table->decimal('ankle', 5, 2)->nullable();
            $table->date('measured_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_sizes');
    }
};
