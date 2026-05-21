<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            // Foreign key to users (who created it)
            if (!Schema::hasColumn('tickets', 'created_by_user_id')) {
                $table->unsignedBigInteger('created_by_user_id')->nullable()->after('created_by');
            }

            // Assigned to (user id)
            if (!Schema::hasColumn('tickets', 'assigned_to')) {
                $table->unsignedBigInteger('assigned_to')->nullable()->after('created_by_user_id');
            }

            // Category string + category_id FK
            if (!Schema::hasColumn('tickets', 'category')) {
                $table->string('category')->nullable()->after('assigned_to');
            }
            if (!Schema::hasColumn('tickets', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('category');
            }

            // Status enum (tickets1 values)
            if (!Schema::hasColumn('tickets', 'status')) {
                $table->enum('status', ['open', 'in_progress', 'on_hold', 'completed', 'closed'])
                      ->default('open')
                      ->after('category_id');
            }

            // Due date
            if (!Schema::hasColumn('tickets', 'due_date')) {
                $table->date('due_date')->nullable()->after('status');
            }
            if (!Schema::hasColumn('tickets', 'due_date_reason')) {
                $table->text('due_date_reason')->nullable()->after('due_date');
            }

            // Assigned team member
            if (!Schema::hasColumn('tickets', 'assigned_team_member_id')) {
                $table->unsignedBigInteger('assigned_team_member_id')->nullable()->after('due_date_reason');
            }
        });

        // ── STEP 2: Copy all rows from tickets1 into tickets ────────
        $rows = DB::table('tickets1')->get();

        foreach ($rows as $row) {
            // Check if this ticket1 row already exists in tickets
            // (match by description to avoid duplicates on re-run)
            $exists = DB::table('tickets')
                        ->where('description', $row->description)
                        ->where('created_by_user_id', $row->created_by)
                        ->exists();

            if (!$exists) {
                DB::table('tickets')->insert([
                    'created_by_user_id'      => $row->created_by,
                    'assigned_to'             => $row->assigned_to,
                    'description'             => $row->description,
                    'category'                => $row->category,
                    'category_id'             => $row->category_id,
                    'status'                  => $row->status,
                    'attachment'              => $row->attachment,
                    'priority'                => strtolower($row->priority), // normalise case
                    'due_date'                => $row->due_date,
                    'due_date_reason'         => $row->due_date_reason,
                    'assigned_team_member_id' => $row->assigned_team_member_id,
                    'created_at'              => $row->created_at,
                    'updated_at'              => $row->updated_at,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Remove the added columns (rolls back the schema change)
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'created_by_user_id',
                'assigned_to',
                'category',
                'category_id',
                'status',
                'due_date',
                'due_date_reason',
                'assigned_team_member_id',
            ]);
        });
        // Note: rolled-back rows are NOT deleted to avoid accidental data loss
    }
};