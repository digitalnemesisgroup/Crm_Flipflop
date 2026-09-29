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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('status_new')->default('created');
        });
        
        \Illuminate\Support\Facades\DB::statement('UPDATE projects SET status_new = status');
        
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('projects', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Irreversible without creating another temporary column.
        });
    }
};
