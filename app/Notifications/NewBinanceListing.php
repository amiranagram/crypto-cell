<?php

namespace App\Notifications;

use App\Models\BinanceListing;
use App\Models\TelegramChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class NewBinanceListing extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(private BinanceListing $binanceListing)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  TelegramChat  $notifiable
     * @return array
     */
    public function via(TelegramChat $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return TelegramMessage
     */
    public function toTelegram(TelegramChat $notifiable): TelegramMessage
    {
        return TelegramMessage::create()
            ->to($notifiable->chat_id)
            ->content($this->binanceListing->title)
            ->button('View', $this->binanceListing->url);
    }
}
