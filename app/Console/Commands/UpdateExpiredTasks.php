<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use Carbon\Carbon;

class UpdateExpiredTasks extends Command
{
    protected $signature = 'app:update-expired-tasks';
    protected $description = 'Updates the status of overdue tasks to "expired"';

    public function handle()
    {
        $this->info('Checking for expired tasks...');

        $expiredTasks = Task::whereIn('status', ['pending', 'in_progress'])
                            ->where('due_date', '<', Carbon::now())
                            ->get();

        if ($expiredTasks->isEmpty()) {
            $this->info('No expired tasks found.');
            return;
        }

        foreach ($expiredTasks as $task) {
            $task->status = 'expired';
            $task->save();
            $this->line("Task #{$task->id} ({$task->title}) has been marked as expired.");
        }

        $this->info('Expired tasks updated successfully.');
    }
}