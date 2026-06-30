<?php

namespace App\Http\Controllers;

use App\Models\TicketOption;

use Illuminate\Http\Request;

class TicketOptionController extends Controller
{
    public function index()
{
    $statuses   = TicketOption::where('type', 'status')->orderBy('sort_order')->get();
    $priorities = TicketOption::where('type', 'priority')->orderBy('sort_order')->get();
    $roles      = TicketOption::where('type', 'role')->orderBy('sort_order')->get();

    return view('ticketsystem.admin.ticket_options', compact('statuses', 'priorities', 'roles'));
}

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:status,priority',
            'label'      => 'required|string|max:100',
            'color'      => 'required|string',
            'text_color' => 'required|string',
        ]);

        $value = strtolower(str_replace(' ', '_', trim($request->label)));

        if (TicketOption::where('type', $request->type)->where('value', $value)->exists()) {
            return back()->with('error', 'A ' . $request->type . ' with this name already exists.');
        }

        $maxOrder = TicketOption::where('type', $request->type)->max('sort_order') ?? 0;

        TicketOption::create([
            'type'       => $request->type,
            'value'      => $value,
            'label'      => $request->label,
            'color'      => $request->color,
            'text_color' => $request->text_color,
            'is_active'  => true,
            'sort_order' => $maxOrder + 1,
        ]);

        return back()->with('success', ucfirst($request->type) . ' "' . $request->label . '" added successfully.');
    }

    public function toggle(TicketOption $ticketOption)
    {
        $ticketOption->update(['is_active' => !$ticketOption->is_active]);
        return back()->with('success', 'Option ' . ($ticketOption->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function destroy(TicketOption $ticketOption)
    {
        $ticketOption->delete();
        return back()->with('success', 'Option deleted.');
    }

    public function getOptions($type)
    {
        $options = TicketOption::where('type', $type)
                               ->where('is_active', true)
                               ->orderBy('sort_order')
                               ->get(['value', 'label', 'color', 'text_color']);

        return response()->json($options);
    }

    // --- Role methods ---

    public function storeRole(Request $request)
{
    $request->validate(['role_name' => 'required|string|max:50']);

    $value = strtolower(str_replace(' ', '_', trim($request->role_name)));

    if (TicketOption::where('type', 'role')->where('value', $value)->exists()) {
        return back()->with('error', 'This role already exists.');
    }

    $maxOrder = TicketOption::where('type', 'role')->max('sort_order') ?? 0;

    TicketOption::create([
        'type'       => 'role',
        'value'      => $value,
        'label'      => $request->role_name,
        'color'      => '#f3f4f6',
        'text_color' => '000000',
        'is_active'  => true,
        'sort_order' => $maxOrder + 1,
    ]);

    return back()->with('success', 'Role "' . $request->role_name . '" added.');
}

public function destroyRole($id)
{
    TicketOption::findOrFail($id)->delete();
    return back()->with('success', 'Role deleted.');
}
}