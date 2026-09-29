<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('special_task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('data'); // stores the user's answers to the custom form
            $table->string('status')->default('pending'); // pending, approved, rejected
            $table->text('admin_feedback')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('special_task_submissions');
    }
};
