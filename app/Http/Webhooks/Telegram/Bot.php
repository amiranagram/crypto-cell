<?php

namespace App\Http\Webhooks\Telegram;

use App\Http\Webhooks\Telegram\Commands\StartCommand;
use App\Http\Webhooks\Telegram\Commands\SubscribeBinanceCommand;

class Bot
{
    public const COMMANDS = [
        'start' => StartCommand::class,
        'sub_binance' => SubscribeBinanceCommand::class,
    ];

    public const LAUNCH_DATE = '2022-01-10 14:00';
}
