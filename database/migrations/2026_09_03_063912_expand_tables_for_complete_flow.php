<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'mobile')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('mobile')->nullable()->unique();
                $table->string('profile_picture')->nullable();
                $table->json('skills')->nullable();
                $table->date('joining_date')->nullable();
                $table->string('employment_type')->nullable(); // Full-time, Part-time, Contract
                $table->json('bank_details')->nullable();
                $table->string('status')->default('Active'); // Active, Inactive, Suspended
            });
        }

        if (!Schema::hasColumn('clients', 'gst_number')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->string('gst_number')->nullable();
            });
        }

        if (!Schema::hasColumn('projects', 'employee_payout_percentage')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->decimal('employee_payout_percentage', 5, 2)->nullable();
            });
        }

        if (!Schema::hasColumn('leads', 'status_new')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('status_new')->default('new');
                $table->string('work_type')->nullable(); // Calling, Software Sales, etc.
            });
            
            DB::statement('UPDATE leads SET status_new = status');
            
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('status');
            });
            
            Schema::table('leads', function (Blueprint $table) {
                $table->renameColumn('status_new', 'status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'profile_picture', 'skills', 'joining_date', 'employment_type', 'bank_details', 'status']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['company_name', 'gst_number', 'address']);
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('employee_payout_percentage');
        });
        
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('work_type');
            // Reverting status to enum is skipped for brevity
        });
    }
};
