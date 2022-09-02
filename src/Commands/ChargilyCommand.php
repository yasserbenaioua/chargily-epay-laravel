<?php

namespace YasserBenaioua\Chargily\Commands;

use Illuminate\Console\Command;

class ChargilyCommand extends Command
{
    public $signature = 'chargily-epay-laravel';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
