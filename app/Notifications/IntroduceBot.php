<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class IntroduceBot extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param int $notifiable
     * @return array
     */
    public function via(int $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param int $notifiable
     * @return TelegramMessage
     */
    public function toTelegram(int $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable)
            ->content("Hello I am CryptoCell bot.\nI am here to give you the latest crypto alerts.\n\nCommands:\n\n/sub_binance - Subscribe for latest Binance crypto alerts.");
    }
}
