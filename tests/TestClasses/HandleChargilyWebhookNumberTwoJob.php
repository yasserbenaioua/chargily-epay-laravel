<?php

namespace YasserBenaioua\Chargily\Tests\TestClasses;

use Illuminate\Contracts\Queue\ShouldQueue;
use YasserBenaioua\Chargily\Models\ChargilyWebhookCall;

class HandleChargilyWebhookNumberTwoJob implements ShouldQueue
{
    public function __construct(
        public ChargilyWebhookCall $webhookCall
    ) {
    }

    public function handle()
    {
    }
}
