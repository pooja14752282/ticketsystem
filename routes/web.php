<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AdminTicketController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\SupportTeamController;
use App\Http\Controllers\TicketOptionController;
use App\Http\Controllers\notificationcontroller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketReviewController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard charts
Route::get('/dashboard/chart-data', [DashboardController::class, 'chartData']);

// Dashboard View All button
Route::get('/admin/tickets', [App\Http\Controllers\TicketController::class, 'allTickets'])
    ->name('admin.tickets.index');

// Register routes
Route::get("register", [AuthController::class, "register"])->name('register');
Route::post("register", [AuthController::class, "store"])->name('register.store');

// Login routes
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginStore'])->name('login.store');

// Forgot Password routes
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('forgot.password');
Route::post('/forgot-password', [AuthController::class, 'forgotPasswordStore'])->name('forgot.password.store');

// Reset Password routes
Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPasswordStore'])->name('password.update');

// ===========================================================
// AUTHENTICATED ROUTES
// ===========================================================
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // Ticket System — own tickets
    Route::get('/ticketsystem/my',     [TicketController::class, 'myTickets'])->name('ticketsystem.my');
    Route::get('/ticketsystem/create', [TicketController::class, 'create'])->name('ticketsystem.create');
    Route::post('/ticketsystem/store', [TicketController::class, 'store'])->name('ticketsystem.store');
    Route::delete('/ticketsystem/{ticket}', [TicketController::class, 'destroy'])->name('ticketsystem.destroy');
    Route::patch('/ticketsystem/{ticket}/due-date', [TicketController::class, 'updateDueDate'])->name('ticketsystem.updateDueDate');

    // Tickets assigned to the current user
    Route::get('/ticketsystem/assigned', [TicketController::class, 'assignedTickets'])->name('ticketsystem.assigned');

    // Attachment download
    Route::get('/tickets/{id}/download', [TicketController::class, 'downloadAttachment'])->name('tickets.download');

    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    
    // Ticket Review
    Route::post('/ticketsystem/branch/{ticket}/review', [TicketReviewController::class, 'store'])->name('ticket.review.store');

    // ===========================================================
    // SUPPORT TEAM ROUTES — su = 4
    // ===========================================================
    Route::get('/support/tickets',          [SupportTeamController::class, 'myAssignedTickets'])->name('support.tickets');
    Route::get('/support/tickets/{ticket}', [SupportTeamController::class, 'showTicket'])->name('support.ticket.show');

    // ===========================================================
    // SHARED: ADMIN + SUPPORT
    // ===========================================================
    Route::middleware(['role:admin,support'])->group(function () {
        Route::patch('/ticketsystem/{ticket}/status', [TicketController::class, 'updateStatus'])->name('ticketsystem.updateStatus');
        Route::patch('/tickets/{ticket}/status',      [TicketController::class, 'updateStatus']);
        Route::patch('/tickets/{ticket}/priority',    [TicketController::class, 'updatePriority']);
    });

    // ===========================================================
    // ADMIN ONLY
    // ===========================================================
    Route::middleware(['role:admin'])->group(function () {

        // All Tickets (admin view)
        Route::get('/ticketsystem/branch',            [AdminTicketController::class, 'index'])->name('admin.tickets.index');
        Route::get('/ticketsystem/branch/{ticket}',    [AdminTicketController::class, 'show'])->name('admin.tickets.show');
        Route::delete('/ticketsystem/branch/{ticket}', [AdminTicketController::class, 'destroy'])->name('admin.tickets.destroy');

        // Due Dates
        Route::get('/tickets/due-dates',           [TicketController::class, 'dueDatesPage'])->name('admin.tickets.duedates');
        Route::patch('/tickets/{ticket}/due-date', [TicketController::class, 'updateDueDate'])->name('tickets.updateDueDate');

        // Reassign
        Route::patch('/tickets/{ticket}/reassign', [AdminTicketController::class, 'reassign'])->name('admin.tickets.reassign');

        // Ticket Categories
        Route::get('/ticketcategory',                          [TicketCategoryController::class, 'index'])->name('admin.ticket-categories.index');
        Route::get('/ticketcategory/create',                   [TicketCategoryController::class, 'create'])->name('admin.ticket-categories.create');
        Route::post('/ticketcategory',                         [TicketCategoryController::class, 'store'])->name('admin.ticket-categories.store');
        Route::get('/ticketcategory/{ticketCategory}',         [TicketCategoryController::class, 'show'])->name('admin.ticket-categories.show');
        Route::get('/ticketcategory/{ticketCategory}/edit',    [TicketCategoryController::class, 'edit'])->name('admin.ticket-categories.edit');
        Route::put('/ticketcategory/{ticketCategory}',         [TicketCategoryController::class, 'update'])->name('admin.ticket-categories.update');
        Route::post('/ticketcategory/{ticketCategory}/toggle', [TicketCategoryController::class, 'toggleStatus'])->name('admin.ticket-categories.toggle');
        Route::delete('/ticketcategory/{ticketCategory}',      [TicketCategoryController::class, 'destroy'])->name('admin.ticket-categories.destroy');

        // Support Team management
        Route::get('/admin/support-team',                        [SupportTeamController::class, 'index'])->name('admin.support-team.index');
        Route::get('/admin/support-team/create',                 [SupportTeamController::class, 'create'])->name('admin.support-team.create');
        Route::post('/admin/support-team',                       [SupportTeamController::class, 'store'])->name('admin.support-team.store');
        Route::delete('/admin/support-team/{supportTeam}',       [SupportTeamController::class, 'destroy'])->name('admin.support-team.destroy');
        Route::get('/admin/support-team/{supportTeam}/edit',     [SupportTeamController::class, 'edit'])->name('admin.support-team.edit');
        Route::put('/admin/support-team/{supportTeam}',          [SupportTeamController::class, 'update'])->name('admin.support-team.update');
        Route::patch('/admin/support-team/{supportTeam}/toggle', [SupportTeamController::class, 'toggle'])->name('admin.support-team.toggle');

        // Ticket Options
        Route::get('/admin/ticket-options',                         [TicketOptionController::class, 'index'])->name('admin.ticket-options.index');
        Route::post('/admin/ticket-options',                        [TicketOptionController::class, 'store'])->name('admin.ticket-options.store');
        Route::patch('/admin/ticket-options/{ticketOption}/toggle', [TicketOptionController::class, 'toggle'])->name('admin.ticket-options.toggle');
        Route::delete('/admin/ticket-options/{ticketOption}',       [TicketOptionController::class, 'destroy'])->name('admin.ticket-options.destroy');

        // Roles
        Route::post('/roles',        [TicketOptionController::class, 'storeRole'])->name('roles.store');
        Route::delete('/roles/{id}', [TicketOptionController::class, 'destroyRole'])->name('roles.destroy');
    });

    // Available to any authenticated user
    Route::get('/ticket-options/{type}', [TicketOptionController::class, 'getOptions'])->name('ticket-options.get');
});