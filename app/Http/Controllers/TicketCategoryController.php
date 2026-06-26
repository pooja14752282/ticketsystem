<?php

namespace App\Http\Controllers;

use App\Models\TicketCategory;
use App\Models\User;
use App\Models\TicketSupportTeam;
use Illuminate\Http\Request;


class TicketCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = TicketCategory::with('assignee');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $perPage = $request->input('per_page', 15);
        $categories = $query->latest()->paginate($perPage);

        return view('ticketsystem.admin.categories.index', compact('categories'));
    }

    public function create()
{
    $users = \App\Models\TicketSupportTeam::where('is_active', true)->get(); 
    return view('ticketsystem.admin.categories.create', compact('users'));
}

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:100|unique:ticket_categories,name',
            'assign_to' => 'nullable|exists:users,id',
            'email'     => 'nullable|email|max:255',
            'status'    => 'required|in:active,inactive',
            'app_name'  => 'required|in:' . implode(',', array_keys(SupportTeam::APPS)), 
        ]);

        TicketCategory::create($request->only('name', 'assign_to', 'email', 'status', 'app_name')); 

        return redirect()->route('admin.ticket-categories.index')
            ->with('success', 'Ticket Category created successfully.');
    }

    public function show(TicketCategory $ticketCategory)
    {
        $ticketCategory->load('assignee');
        return view('ticketsystem.admin.categories.show', compact('ticketCategory'));
    }

    public function edit(TicketCategory $ticketCategory)
{
    $users = \App\Models\SupportTeam::where('is_active', true)->get(); // ✅ changed
    return view('ticketsystem.admin.categories.edit', compact('ticketCategory', 'users'));
}
    public function update(Request $request, TicketCategory $ticketCategory)
    {
        $request->validate([
            'name'      => 'required|string|max:100|unique:ticket_categories,name,' . $ticketCategory->id,
            'assign_to' => 'nullable|exists:users,id',
            'email'     => 'nullable|email|max:255',
            'status'    => 'required|in:active,inactive',
            'app_name'  => 'nullable|in:' . implode(',', array_keys(SupportTeam::APPS)), // ✅ added
        ]);

        $ticketCategory->update($request->only('name', 'assign_to', 'email', 'status', 'app_name')); // ✅ added app_name

        return redirect()->route('admin.ticket-categories.index')
            ->with('success', 'Ticket Category updated successfully.');
    }

    public function toggleStatus(TicketCategory $ticketCategory)
    {
        $ticketCategory->update([
            'status' => $ticketCategory->status === 'active' ? 'inactive' : 'active'
        ]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(TicketCategory $ticketCategory)
    {
        $ticketCategory->delete();
        return back()->with('success', 'Category deleted.');
    }
}