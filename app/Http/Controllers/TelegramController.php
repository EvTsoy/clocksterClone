<?php

namespace App\Http\Controllers;

use Log;
use App\Conversation\Conversation;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramController extends Controller
{
    public function process()
    {
        $update = Telegram::bot()->getWebhookUpdate();

        Log::debug('Telegram.process', [
            'update' => $update,
        ]);


        //Начало диалога
        $conversation = new Conversation();
        $conversation->start($update);
    }
}
