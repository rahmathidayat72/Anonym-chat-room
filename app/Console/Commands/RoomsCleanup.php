<?php

namespace App\Console\Commands;

use App\Models\Room;
use Illuminate\Console\Command;

class RoomsCleanup extends Command
{
    protected $signature = 'rooms:cleanup';

    protected $description = 'Delete expired rooms and all related data';

    public function handle(): void
    {
        $count = Room::where('expired_at', '<=', now())->delete();

        $this->info("Deleted {$count} expired room(s).");
    }
}
