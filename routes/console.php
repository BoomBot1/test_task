<?php

use App\Console\Commands\Integrations\RunReestrApiOrderCompleteCheckCommand;
use Illuminate\Support\Facades\Schedule;

if (config('telescope.enabled')) {
    Schedule::command('telescope:prune --hours=48')->hourly();
}

Schedule::command('sanctum:prune-expired --hours=0')->hourly();
Schedule::command('sanctum:prune-refresh-expired --hours=0')->daily();

Schedule::command(RunReestrApiOrderCompleteCheckCommand::class)
    ->everyMinute();
