<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing single assignments to the new many-to-many table
        $tickets = DB::table('tickets')
            ->whereNotNull('assigned_to')
            ->select('id', 'assigned_to', 'created_at')
            ->get();
        
        foreach ($tickets as $ticket) {
            DB::table('ticket_assignments')->insert([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->assigned_to,
                'assigned_at' => $ticket->created_at,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear the assignments table
        DB::table('ticket_assignments')->truncate();
    }
};
