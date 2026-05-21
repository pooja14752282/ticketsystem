<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReview;
use Illuminate\Http\Request;

class TicketReviewController extends Controller
{
    public function store(Request $request, Ticket $ticket)
    {
        $request->validate([
            'notes'             => 'required|string',
            'resolution_status' => 'required|string',
        ]);

        // Prevent duplicate reviews — one review per ticket
        TicketReview::updateOrCreate(
            ['ticket_id' => $ticket->id],
            [
                'support_member_id' => auth()->id(),
                'notes'             => $request->notes,
                'resolution_status' => $request->resolution_status,
            ]
        );

        return back()->with('success', 'Review submitted successfully.');
    }
}