<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('ticket_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('support_member_id')->constrained('users')->onDelete('cascade');
            $table->text('notes');
            $table->string('resolution_status'); // Resolved, Partially resolved, Unresolved, Escalated
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket_reviews');
    }
}