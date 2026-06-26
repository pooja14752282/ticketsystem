<?php

namespace App\Http\Controllers;

use App\Models\TicketSupportTeam;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketSupportTeam as SupportTeam;

class SupportTeamController extends Controller
{
    public function index()
    {
        $teams = SupportTeam::orderBy('created_at', 'desc')->get();
        return view('TicketSystem.admin.support_team.index', compact('teams'));
    }

    public function create()
{
    $apps  = SupportTeam::APPS;
    $roles = \App\Models\TicketOption::where('type', 'role')
                                     ->where('is_active', true)
                                     ->orderBy('sort_order')
                                     ->get();

    return view('TicketSystem.admin.support_team.create', compact('apps', 'roles'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'app_assigned' => 'required',
            'password'     => 'required|min:6|confirmed',
        ]);

        // Create login account in users table
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => 'support',
        ]);

        // Create support team member linked to user
        TicketSupportTeam::create([
            'user_id'      => $user->id,
            'name'         => $request->name,
            'email'        => $request->email,
            'app_assigned' => $request->app_assigned,
            'is_active'    => true,
        ]);

        return redirect()->route('admin.support-team.index')
                         ->with('success', 'Member added and login account created!');
    }

    public function edit(SupportTeam $supportTeam)
    {
        $apps = SupportTeam::APPS;
        return view('TicketSystem.admin.support_team.edit', compact('supportTeam', 'apps'));
    }

    public function update(Request $request, SupportTeam $supportTeam)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:support_teams,email,' . $supportTeam->id,
            'app_assigned' => 'required|in:' . implode(',', array_keys(SupportTeam::APPS)),
        ]);

        $supportTeam->update($request->only('name', 'email', 'app_assigned'));

        // Also update the linked user record
        if ($supportTeam->user_id) {
            User::where('id', $supportTeam->user_id)
                ->update(['name' => $request->name, 'email' => $request->email]);
        }

        return redirect()->route('admin.support-team.index')
                         ->with('success', 'Team member updated successfully!');
    }

    public function toggle(SupportTeam $supportTeam)
    {
        $supportTeam->update(['is_active' => !$supportTeam->is_active]);

        return redirect()->route('admin.support-team.index')
                         ->with('success', 'Member status updated.');
    }

    public function destroy(SupportTeam $supportTeam)
    {
        $supportTeam->delete();
        return redirect()->route('admin.support-team.index')
                         ->with('success', 'Team member removed.');
    }

    public function myAssignedTickets(Request $request)
    {
        $user   = auth()->user();
        $member = SupportTeam::where('email', $user->email)->first();

       if (!$member) {
    return view('TicketSystem.support.tickets', [
        'tickets'    => new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15),
        'member'     => null,
        'categories' => collect(),
    ]);
}

        $query = Ticket::where('assigned_team_member_id', $member->id);

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

        $tickets    = $query->latest()->paginate(10);
        $categories = Ticket::where('assigned_team_member_id', $member->id)
                            ->distinct()->pluck('category');

        return view('TicketSystem.support.tickets', compact('tickets', 'member', 'categories'));
    }

   public function showTicket(Ticket $ticket)
{
    $ticket->load('review.supportMember');
    $statuses   = \App\Models\TicketOption::where('type', 'status')->where('is_active', true)->orderBy('sort_order')->get();
    $priorities = \App\Models\TicketOption::where('type', 'priority')->where('is_active', true)->orderBy('sort_order')->get();

    return view('TicketSystem.support.show_ticket', compact('ticket', 'statuses', 'priorities'));
}
}