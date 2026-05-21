<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_options', function (Blueprint $table) {
            $table->id();
            $table->string('type');   // 'status' or 'priority'
            $table->string('value');  // e.g. 'in_progress', 'urgent'
            $table->string('label');  // e.g. 'In Progress', 'Urgent'
            $table->string('color')->nullable(); // badge color e.g. '#dbeafe'
            $table->string('text_color')->nullable(); // badge text color
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_options');
    }
};