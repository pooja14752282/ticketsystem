<?php

namespace App\Mail;

use App\Models\Ticket;
use App\Models\TicketReview;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketReviewedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Ticket $ticket;
    public TicketReview $review;
    public User $reviewer;

    public function __construct(Ticket $ticket, TicketReview $review, User $reviewer)
    {
        $this->ticket   = $ticket;
        $this->review   = $review;
        $this->reviewer = $reviewer;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ticket ' . $this->ticket->ticket_id . ' — ' . $this->review->resolution_status,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_reviewed',
        );
    }
}