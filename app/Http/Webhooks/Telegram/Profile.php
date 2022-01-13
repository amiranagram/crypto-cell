<?php

namespace App\Http\Webhooks\Telegram;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\WebhookClient\WebhookProfile\WebhookProfile;

class Profile implements WebhookProfile
{
    /**
     * @param Request $request
     * @return bool
     */
    public function shouldProcess(Request $request): bool
    {
        if (! $request->collect('message.entities')->contains('type', 'bot_command')) {
            return false;
        }

        if (is_null($message = $request->input('message.text'))) {
            return false;
        }

        if (! is_string($message)) {
            return false;
        }

        return $this->hasValidCommand($message);
    }

    /**
     * @param string $text
     * @return bool
     */
    private function hasValidCommand(string $text): bool
    {
        $command = Str::of($text)->explode(' ')->first();

        if (! str_starts_with($command, '/')) {
            return false;
        }

        $command = ltrim($command, '/');

        return array_key_exists($command, Bot::COMMANDS);
    }
}
