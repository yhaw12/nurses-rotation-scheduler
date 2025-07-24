<?php

// app/Console/Commands/DeleteExpiredRosters.php

namespace App\Console\Commands;

use App\Models\Roster;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DeleteExpiredRosters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rosters:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete rosters that have expired';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        Log::debug('Scheduler checked at ' . now()->toDateTimeString());

        $today = now();
        $expiredRosters = Roster::where('end_date', '<', $today)->get();

        foreach ($expiredRosters as $roster) {
            try {
                // If you have cascade deletes on the assignments relation, you can skip the next line.
                $roster->assignments()->delete();

                $roster->delete();
                Log::info("Deleted expired roster ID: {$roster->id}");
            } catch (\Throwable $e) {
                Log::error("Failed to delete expired roster ID {$roster->id}: {$e->getMessage()}");
            }
        }

        $count = $expiredRosters->count();
        $this->info("Checked for expired rosters. Deleted {$count} roster(s).");

        return 0;
    }
}
