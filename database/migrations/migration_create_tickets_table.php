<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create ticket_categories table
        Schema::create('ticket_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('assign_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('email')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Add category_id to existing tickets table
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('category')
                  ->constrained('ticket_categories')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        // Remove category_id from tickets first
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        // Then drop ticket_categories
        Schema::dropIfExists('ticket_categories');
    }
};
