<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class RunSpecificTask extends Command
{
    protected $signature = 'schedule:run-task {name}';
    protected $description = 'Run a specific scheduled task by description name';

    protected $schedule;

    public function __construct(Schedule $schedule)
    {
        parent::__construct();
        $this->schedule = $schedule;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $events = collect($this->schedule->events())->filter(function ($event) use ($name) {
            return $event->description === $name;
        });

        if ($events->isEmpty()) {
            $this->error("No scheduled task found with the description '{$name}'");
            return;
        }

        foreach ($events as $event) {
            $this->showEventDetails($event);
            $event->run($this->laravel);
            $this->info("Scheduled task '{$name}' has been run.");
        }
    }

    protected function showEventDetails($event)
    {
        $this->info("Task Details:");
        $this->line("Description: " . $event->description);
        $this->line("Command: " . $event->command);
        $this->line("Expression: " . $event->expression);
        $this->line("Timezone: " . $event->timezone);
        $this->line("Mutex: " . ($event->mutexName() ?? 'N/A'));
    }
    
}
