<?php

namespace App\Support\Commands;

use Illuminate\Console\Command;

final class DumpMemory
{
    public static function dumpMemory(Command $command): void
    {
        $memory = memory_get_usage(true) / 1024 / 1024;
        $memoryPeak = memory_get_peak_usage(true) / 1024 / 1024;

        $command->info("Memory: $memory MB");
        $command->info("Memory peak: $memoryPeak MB");
    }
}
