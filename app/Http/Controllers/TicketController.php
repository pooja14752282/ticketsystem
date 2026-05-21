<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SupportTeam;

class TicketController extends Controller
{
    // No constructor needed - middleware is handled in routes/web.php

    // My Tickets list
    public function myTickets(Request $request)
{
    $query = Ticket::where('created_by', Auth::id())
        ->with(['assignee']);

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
        $query->where('category', $request->category);
    }

    $tickets = $query->latest()->get();

    $stats = [
        'high'   => Ticket::where('created_by', Auth::id())->where('priority', 'high')->count(),
        'open'   => Ticket::where('created_by', Auth::id())->where('status', 'open')->count(),
        'onhold' => Ticket::where('created_by', Auth::id())->where('status', 'on_hold')->count(),
        'urgent' => Ticket::where('created_by', Auth::id())->where('priority', 'urgent')->count(),
    ];

    $categories = Ticket::where('created_by', Auth::id())
        ->distinct()->pluck('category');

    $statuses   = \App\Models\TicketOption::where('type', 'status')->where('is_active', true)->orderBy('sort_order')->get();
    $priorities = \App\Models\TicketOption::where('type', 'priority')->where('is_active', true)->orderBy('sort_order')->get();

    return view('ticketsystem.my_tickets', compact('tickets', 'stats', 'categories', 'statuses', 'priorities'));
}

    // Tickets assigned to me
    public function assignedTickets(Request $request)
{
    $teamMember = SupportTeam::where('email', Auth::user()->email)->first();

    $query = Ticket::with(['creator']);

    if ($teamMember) {
        $query->where('assigned_team_member_id', $teamMember->id);
    } else {
        $query->where('assigned_to', Auth::id());
    }

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
        $query->where('category', $request->category);
    }

    $tickets    = $query->latest()->get();
    $categories = Ticket::where('assigned_to', Auth::id())->distinct()->pluck('category');
    $statuses   = \App\Models\TicketOption::where('type', 'status')->where('is_active', true)->orderBy('sort_order')->get();
    $priorities = \App\Models\TicketOption::where('type', 'priority')->where('is_active', true)->orderBy('sort_order')->get();

    return view('ticketsystem.assigned_tickets', compact('tickets', 'categories', 'statuses', 'priorities'));
}

    // Show create form
    public function create()
{
    $admins         = User::where('role', 'admin')->get();
    $supportMembers = SupportTeam::where('is_active', true)->get();
    $priorities     = \App\Models\TicketOption::where('type', 'priority')->where('is_active', true)->orderBy('sort_order')->get();

    return view('ticketsystem.create_ticket', compact('admins', 'supportMembers', 'priorities'));
}

    // Store new ticket 
   public function store(Request $request)
{
    $request->validate([
        'description' => 'required|string|max:500',
        'category'    => 'required|in:' . implode(',', array_keys(\App\Models\SupportTeam::APPS)),
        'priority'    => 'required|in:low,high,urgent',
        'due_date'    => 'nullable|date',
        'attachment'  => 'nullable|file|mimes:png,jpg,jpeg,pdf,doc,docx|max:10240',
    ]);

    $supportMember = \App\Models\SupportTeam::where('app_assigned', $request->category)
                        ->where('is_active', true)
                        ->first();

    $dueDays = ['low' => 5, 'high' => 3, 'urgent' => 2];
    $dueDate = $request->due_date
        ?? now()->addDays($dueDays[$request->priority])->toDateString();

    $attachmentPath = null;
    if ($request->hasFile('attachment')) {
        $attachmentPath = $request->file('attachment')->store('ticket-attachments', 'public');
    }

    $ticket = Ticket::create([
        'created_by'              => Auth::id(),
        'assigned_to'             => null,
        'assigned_team_member_id' => $supportMember?->id,
        'description'             => $request->description,
        'category'                => $request->category,
        'priority'                => $request->priority,
        'status'                  => 'open',
        'attachment'              => $attachmentPath,
        'due_date'                => $dueDate,
    ]);

    // Notify the assigned support member
    if ($supportMember && $supportMember->user_id) {
        \App\Models\Notification::create([
            'user_id'   => $supportMember->user_id,
            'ticket_id' => $ticket->id,
            'title'     => 'New Ticket Assigned',
            'message'   => 'Ticket #' . $ticket->id . ' has been assigned to you.',
            'type'      => 'info',
            'is_read'   => false,
        ]);
    }

    return redirect()->route('ticketsystem.my')->with('success', 'Ticket created and auto-assigned successfully.');
}

    // Show single ticket (JSON for modal)
    public function show(Ticket $ticket)
    {
        $user = Auth::user();

        $allowedTeamMember = \App\Models\SupportTeam::where('user_id', $user->id)->first();
        $teamMemberId = $allowedTeamMember?->id;

        if (
            $ticket->created_by              !== $user->id &&
            $ticket->assigned_to             !== $user->id &&
            $ticket->assigned_team_member_id !== $teamMemberId
        ) {
            abort(403);
        }

        return response()->json([
            'id'          => $ticket->id,
            'description' => $ticket->description,
            'category'    => $ticket->category,
            'priority'    => $ticket->priority,
            'status'      => $ticket->status,
            'created_at'  => $ticket->created_at->format('d M Y, h:i A'),
            'attachment'  => $ticket->attachment ? asset('storage/' . $ticket->attachment) : null,
            'filename'    => $ticket->attachment ? basename($ticket->attachment) : null,
            'is_image'    => $ticket->attachment
                                ? in_array(strtolower(pathinfo($ticket->attachment, PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','webp'])
                                : false,
        ]);
    }

    // Update ticket status
   public function updateStatus(Request $request, $id)
{
    // Dynamically load valid statuses from DB
    $validStatuses = \App\Models\TicketOption::where('type', 'status')
                        ->where('is_active', true)
                        ->get()
                        ->map(fn($s) => str_replace(' ','_',strtolower($s->name)))
                        ->toArray();

    $request->validate([
        'status' => ['required', \Illuminate\Validation\Rule::in($validStatuses)],
    ]);

    $ticket     = Ticket::findOrFail($id);
    $user       = auth()->user();
    $teamMember = \App\Models\SupportTeam::where('email', $user->email)->first();

    $isAdmin          = $user->role === 'admin';
    $isAssignedMember = $teamMember && $ticket->assigned_team_member_id === $teamMember->id;
    $isAssignedUser   = $ticket->assigned_to === $user->id;

    if (!$isAdmin && !$isAssignedMember && !$isAssignedUser) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $ticket->update(['status' => $request->status]);

    return response()->json(['success' => true]);
}
    // Delete ticket
    public function destroy(Ticket $ticket)
    {
        if ($ticket->created_by !== Auth::id()) {
            abort(403);
        }

        $ticket->delete();

        return back()->with('success', 'Ticket deleted.');
    }

    // Auto-assign team member based on category
    public function assignTeamMember($ticket)
    {
        $appName = $ticket->category->app_name ?? null;

        if ($appName) {
            $member = SupportTeam::where('app_assigned', $appName)
                                 ->where('is_active', true)
                                 ->first();

            if ($member) {
                $ticket->assigned_team_member_id = $member->id;
                $ticket->save();
            }
        }
    }

    // ── Admin: dedicated due dates page ──
    public function dueDatesPage()
    {
        $tickets = Ticket::with(['creator', 'assignee'])
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('TicketSystem.admin.due_dates', compact('tickets'));
    }

    // ── Admin: update due date via PATCH (used by fetch in JS) ──
    public function updateDueDate(Request $request, Ticket $ticket)
{
    if (Auth::user()->role !== 'admin') {
        abort(403);
    }

    $request->validate([
        'due_date'        => 'nullable|date',
        'due_date_reason' => 'nullable|string|max:500',
    ]);

    $ticket->update([
        'due_date'        => $request->due_date,
        'due_date_reason' => $request->due_date_reason,
    ]);

    return response()->json([
        'success'         => true,
        'due_date'        => $ticket->due_date
            ? \Carbon\Carbon::parse($ticket->due_date)->format('d M Y')
            : null,
        'due_date_reason' => $ticket->due_date_reason,
    ]);
}
 public function allTickets(Request $request)
{
    $query = Ticket::with(['creator', 'assignedTeamMember', 'ticketCategory']);

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

    $tickets = $query->latest()->get();

    $stats = [
        'high'   => Ticket::where('priority', 'high')->count(),
        'open'   => Ticket::where('status', 'open')->count(),
        'onhold' => Ticket::where('status', 'on_hold')->count(),
        'urgent' => Ticket::where('priority', 'urgent')->count(),
    ];

    $categories = \App\Models\TicketCategory::orderBy('name')->get();
    $statuses   = \App\Models\TicketOption::where('type', 'status')->where('is_active', true)->orderBy('sort_order')->get();
    $priorities = \App\Models\TicketOption::where('type', 'priority')->where('is_active', true)->orderBy('sort_order')->get();
    $members    = \App\Models\SupportTeam::where('is_active', true)->orderBy('name')->get();

    return view('TicketSystem.admin.all_tickets', compact('tickets', 'stats', 'categories', 'statuses', 'priorities', 'members'));
}
public function updatePriority(Request $request, $id)
{
    $validPriorities = \App\Models\TicketOption::where('type', 'priority')
                        ->where('is_active', true)
                        ->pluck('value')
                        ->toArray();

    $request->validate([
        'priority' => ['required', \Illuminate\Validation\Rule::in($validPriorities)],
    ]);

    $ticket = Ticket::findOrFail($id);
    $user   = auth()->user();

    $teamMember       = \App\Models\SupportTeam::where('email', $user->email)->first();
    $isAdmin          = $user->role === 'admin';
    $isAssignedMember = $teamMember && $ticket->assigned_team_member_id === $teamMember->id;
    $isAssignedUser   = $ticket->assigned_to === $user->id;

    if (!$isAdmin && !$isAssignedMember && !$isAssignedUser) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
    }

    $ticket->update(['priority' => $request->priority]);

    return response()->json(['success' => true]);
}

public function reassign(Request $request, $id)
{
    if (Auth::user()->role !== 'admin') {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
         'new_assignee' => $newMember->name,
    }

    $request->validate([
        'member_id'       => 'required|exists:support_teams,id',
        'reassign_reason' => 'nullable|string|max:500',
    ]);

    $ticket      = Ticket::findOrFail($id);
    $newMember   = \App\Models\SupportTeam::findOrFail($request->member_id);

    $ticket->update([
        'assigned_team_member_id' => $newMember->id,
        'reassign_reason'         => $request->reassign_reason,
    ]);

    // Notify the new support member
    if ($newMember->user_id) {
        \App\Models\Notification::create([
            'user_id'   => $newMember->user_id,
            'ticket_id' => $ticket->id,
            'title'     => 'Ticket Reassigned to You',
            'message'   => 'Ticket #' . $ticket->id . ' has been reassigned to you.'
                         . ($request->reassign_reason ? ' Reason: ' . $request->reassign_reason : ''),
            'type'      => 'info',
            'is_read'   => false,
        ]);
    }

    return response()->json(['success' => true]);
}
}