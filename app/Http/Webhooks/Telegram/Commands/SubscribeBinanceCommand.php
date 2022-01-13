<?php

namespace App\Http\Webhooks\Telegram\Commands;

use App\Models\TelegramChat;
use App\Notifications\SubscribedToAlertsChannel;

class SubscribeBinanceCommand extends Command
{
    public function handle(array $args): void
    {
        $telegramChat = TelegramChat::firstOrCreate([
            'chat_id' => $this->payload['message']['chat']['id'],
            'channel' => 'binance',
        ]);

        $telegramChat->notify(new SubscribedToAlertsChannel);
    }
}
