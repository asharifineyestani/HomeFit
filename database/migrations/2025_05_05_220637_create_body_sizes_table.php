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
            $table->decimal('neck', 5, 2)->nullable();        // اندازه دور گردن (سانتی‌متر)
            $table->decimal('shoulders', 5, 2)->nullable();   // اندازه عرض شانه
            $table->decimal('arm_relaxed', 5, 2)->nullable(); // دور بازوی رها (شل)
            $table->decimal('arm_flexed', 5, 2)->nullable();  // دور بازوی منقبض (خم‌شده)
            $table->decimal('forearm', 5, 2)->nullable();     // دور ساعد
            $table->decimal('wrist', 5, 2)->nullable();       // دور مچ دست
            $table->decimal('chest', 5, 2)->nullable();       // دور سینه
            $table->decimal('stomach', 5, 2)->nullable();     // دور شکم (بالای ناف)
            $table->decimal('waist', 5, 2)->nullable();       // دور کمر (باریک‌ترین بخش)
            $table->decimal('hip', 5, 2)->nullable();         // دور باسن
            $table->decimal('thigh', 5, 2)->nullable();       // دور ران
            $table->decimal('calf', 5, 2)->nullable();        // دور ساق پا
            $table->decimal('ankle', 5, 2)->nullable();       // دور مچ پا
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
