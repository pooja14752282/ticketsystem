<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\SupportTeam;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AdminTicketController extends Controller
{
    public function index(Request $request)
{
    $query = Ticket::with(['creator', 'assignee', 'ticketCategory', 'assignedTeamMember']);

    if ($request->filled('description')) {
        $query->where('description', 'like', '%' . $request->description . '%');
    }
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    $tickets    = $query->latest()->get();
    $categories = TicketCategory::where('status', 'active')->get();
    $members    = SupportTeam::where('is_active', true)->get();
    $statuses   = \App\Models\TicketOption::where('type', 'status')->where('is_active', true)->orderBy('sort_order')->get();
    $priorities = \App\Models\TicketOption::where('type', 'priority')->where('is_active', true)->orderBy('sort_order')->get();

    $stats = [
        'high'   => Ticket::where('priority', 'high')->count(),
        'open'   => Ticket::where('status', 'open')->count(),
        'onhold' => Ticket::where('status', 'on_hold')->count(),
        'urgent' => Ticket::where('priority', 'urgent')->count(),
    ];

    return view('ticketsystem.admin.all_tickets', compact('tickets', 'stats', 'categories', 'members', 'statuses', 'priorities'));
}

   public function show(Ticket $ticket)
{
    $ticket->load('review.supportMember');  
    $ticket->load(['creator', 'assignee', 'ticketCategory', 'assignedTeamMember']);

    $statuses = \App\Models\TicketOption::where('type', 'status')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

    $priorities = \App\Models\TicketOption::where('type', 'priority')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->get();

    return view('ticketsystem.admin.show_ticket', compact('ticket', 'statuses', 'priorities'));
}
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('admin.tickets.index')->with('success', 'Ticket deleted successfully.');
    }

    public function reassign(Request $request, Ticket $ticket)
    {
        $request->validate([
            'assigned_team_member_id' => 'required|exists:support_teams,id',
        ]);

        $oldMember = $ticket->assignedTeamMember;
        $newMember = SupportTeam::find($request->assigned_team_member_id);

        $ticket->update([
            'assigned_team_member_id' => $newMember->id,
        ]);

        // ── In-app notification to old member ──
        if ($oldMember) {
            $oldUser = User::where('email', $oldMember->email)->first();
            if ($oldUser) {
                Notification::create([
                    'user_id'   => $oldUser->id,
                    'title'     => 'Ticket Reassigned',
                    'message'   => "Ticket #{$ticket->id} has been reassigned from you to {$newMember->name}.",
                    'type'      => 'warning',
                    'ticket_id' => $ticket->id,
                ]);

                // ── Email to old member ──
                Mail::raw(
                    "Hello {$oldMember->name},\n\nTicket #{$ticket->id} ({$ticket->description}) has been reassigned from you to {$newMember->name}.\n\nRegards,\nTicket System",
                    function ($m) use ($oldMember, $ticket) {
                        $m->to($oldMember->email)
                          ->subject("Ticket #{$ticket->id} Reassigned");
                    }
                );
            }
        }

        // ── In-app notification to new member ──
        $newUser = User::where('email', $newMember->email)->first();
        if ($newUser) {
            Notification::create([
                'user_id'   => $newUser->id,
                'title'     => 'New Ticket Assigned',
                'message'   => "Ticket #{$ticket->id} has been assigned to you.",
                'type'      => 'info',
                'ticket_id' => $ticket->id,
            ]);

            // ── Email to new member ──
            Mail::raw(
                "Hello {$newMember->name},\n\nTicket #{$ticket->id} ({$ticket->description}) has been assigned to you.\n\nRegards,\nTicket System",
                function ($m) use ($newMember, $ticket) {
                    $m->to($newMember->email)
                      ->subject("New Ticket #{$ticket->id} Assigned to You");
                }
            );
        }

        return response()->json(['success' => true, 'new_assignee' => $newMember->name]);
    }

    
}