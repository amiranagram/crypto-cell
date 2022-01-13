<?php

namespace App\Http\Webhooks\Telegram\Commands;

abstract class Command
{
    /**
     * @param array $payload
     */
    public function __construct(public array $payload)
    {
        //
    }

    /**
     * @param array $payload
     * @return static
     */
    public static function make(array $payload): static
    {
        return new static($payload);
    }

    /**
     * @param array $args
     * @return void
     */
    abstract public function handle(array $args): void;
}
