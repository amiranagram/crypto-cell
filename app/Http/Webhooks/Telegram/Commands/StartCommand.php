<?php

namespace App\Http\Webhooks\Telegram\Commands;

use App\Notifications\IntroduceBot;
use Illuminate\Support\Facades\Notification;

class StartCommand extends Command
{
    /**
     * @inheritDoc
     */
    public function handle(array $args): void
    {
        Notification::send($this->payload['message']['chat']['id'], new IntroduceBot);
    }
}
