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
        Schema::create('question_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');     // کاربر پاسخ‌دهنده
            $table->foreignId('question_id')->constrained()->onDelete('cascade'); // سؤال مرتبط
            $table->text('answer')->nullable();                                    // متن پاسخ
            $table->timestamps();

            $table->unique(['user_id', 'question_id']); // هر کاربر یک پاسخ برای هر سؤال
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_answers');
    }
};
