<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class TelegramChat extends Model
{
    use HasFactory;
    use Notifiable;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'chat_id',
        'channel',
    ];
}
