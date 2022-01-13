<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelegramChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('telegram_chats', static function (Blueprint $table) {
            $table->id();
            $table->bigInteger('chat_id')->index();
            $table->string('channel');
            $table->timestamps();

            $table->unique(['chat_id', 'channel']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('telegram_chats');
    }
}
