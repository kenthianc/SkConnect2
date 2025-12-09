<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;

class UpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update event statuses based on current date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating event statuses...');

        Event::updateEventStatuses();

        $this->info('Event statuses updated successfully!');
        
        return Command::SUCCESS;
    }
}
