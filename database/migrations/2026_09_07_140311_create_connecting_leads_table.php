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
        Schema::create('connecting_leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_number')->unique();
            $table->string('name');
            $table->text('details')->nullable();
            $table->string('purpose')->nullable();
            $table->datetime('connecting_date')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['pending', 'connected', 'approved', 'rejected'])->default('pending');
            $table->text('review')->nullable();
            $table->datetime('next_connecting_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('connecting_leads');
    }
};
