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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->decimal('amount', 12, 2)->nullable()->comment('Amount to be paid on completion');
            $table->date('due_date')->nullable();
            $table->enum('status', ['assigned', 'submitted', 'approved', 'rejected'])->default('assigned');
            
            // Submission details
            $table->text('submission_notes')->nullable();
            $table->json('submission_links')->nullable();
            
            // Verification details
            $table->text('admin_feedback')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
