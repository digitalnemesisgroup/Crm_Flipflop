<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('special_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_template_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('target_count')->default(1);
            $table->date('due_date')->nullable();
            $table->string('status')->default('assigned'); // assigned, submitted, approved, rejected
            $table->decimal('amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('special_tasks');
    }
};
