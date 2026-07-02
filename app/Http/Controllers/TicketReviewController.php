<?php

namespace App\Http\Controllers;

use App\Mail\TicketReviewedMail;
use App\Models\Ticket;
use App\Models\TicketReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TicketReviewController extends Controller
{
    // Fixed admin address that always gets notified
    private const ADMIN_EMAIL = 'admin@research-internship.com';

    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'notes'             => 'required|string',
            'resolution_status' => 'required|string',
        ]);

        // Prevent duplicate reviews — one review per ticket
        $review = TicketReview::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'support_member_id' => auth()->id(),
                'notes'             => $request->notes,
                'resolution_status' => $request->resolution_status,
            ]
        );

        $this->sendReviewNotification($ticket, $review);

        return back()->with('success', 'Review submitted successfully.');
    }

    /**
     * Email the admin, the ticket creator, and the assigned support member
     * about the newly submitted review. Emails are pulled directly from
     * the ticket's own fields/relations rather than looked up by role.
     */
    private function sendReviewNotification(Ticket $ticket, TicketReview $review)
    {
        $reviewer = auth()->user();

        $recipients = collect([self::ADMIN_EMAIL]);

        // Creator — stored directly on the ticket
        if (!empty($ticket->creator_email)) {
            $recipients->push($ticket->creator_email);
        }

        // Assigned support member
        if ($ticket->assignedTeamMember && !empty($ticket->assignedTeamMember->email)) {
            $recipients->push($ticket->assignedTeamMember->email);
        }

        $recipients = $recipients->filter()->unique()->values();

        if ($recipients->isEmpty()) {
            return;
        }

        Mail::to($recipients->all())
            ->send(new TicketReviewedMail($ticket, $review, $reviewer));
    }
}