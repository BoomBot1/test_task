<?php

namespace App\Console\Commands;

use App\Services\Api\v1\Transaction\TransactionService;
use Illuminate\Console\Command;

final class TestCommand extends Command
{
    protected $signature = 'tc';

    public function handle(): void
    {
      //
    }
}
