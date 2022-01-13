<?php

namespace App\Notifications;

use App\Models\TelegramChat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SubscribedToAlertsChannel extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param TelegramChat $notifiable
     * @return array
     */
    public function via(TelegramChat $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param TelegramChat $notifiable
     * @return TelegramMessage
     */
    public function toTelegram(TelegramChat $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->chat_id)
            ->content("Hello there!\nYou will now receive alerts from *{$notifiable->channel}*.");
    }
}
