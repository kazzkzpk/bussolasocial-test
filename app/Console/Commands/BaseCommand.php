<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BaseCommand extends Command
{
    protected $signature = 'app:test';

    public function log(string $message): void
    {
        echo ($message . PHP_EOL);
    }
}
