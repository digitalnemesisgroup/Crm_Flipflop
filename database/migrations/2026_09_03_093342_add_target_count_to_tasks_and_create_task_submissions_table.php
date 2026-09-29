<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add target_count to tasks — e.g. "50 posts total"
        Schema::table('tasks', function (Blueprint $table) {
            if (!Schema::hasColumn('tasks', 'target_count')) {
                $table->unsignedInteger('target_count')->default(1)->after('due_date')
                      ->comment('Total deliverables expected e.g. 50 posts');
            }
        });

        // Each daily submission by the employee
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // The employee provides one or more links per submission
            // (demo link, Instagram link, FB link etc.) — all count as 1 deliverable
            $table->json('links')->comment('Array of links for this single deliverable');
            $table->text('notes')->nullable()->comment('Employee notes for this submission');

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_feedback')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('target_count');
        });
    }
};
