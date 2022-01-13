<?php

namespace App\Jobs;

use App\Http\Webhooks\Telegram\Bot;
use App\Http\Webhooks\Telegram\Commands\Command;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class ProcessTelegramBotWebhook extends ProcessWebhookJob
{
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $message = ltrim($this->webhookCall->payload['message']['text'], '/');

        $args = $this->parseArgsFromMessage($message);
        $command = array_shift($args);

        /** @var Command $commandClass */
        $commandClass = Bot::COMMANDS[$command] ?? null;
        $commandClass && $commandClass::make((array) $this->webhookCall->payload)->handle($args);
    }

    /**
     * Parse the arguments from the message.
     *
     * @param string $message
     * @return array
     */
    protected function parseArgsFromMessage(string $message): array
    {
        preg_match_all('#(?<!\\\\)("|\')(?:[^\\\\]|\\\\.)*?\1|\S+#s', $message, $args);

        return $args[0];
    }
}
