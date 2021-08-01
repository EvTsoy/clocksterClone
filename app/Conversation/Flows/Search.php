<?php

namespace App\Conversation\Flows;

class Search extends AbstractFlow
{
    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'На данный момент подходящих вакансий нет. Как только появится что-то достойное мы обязательно пришлём вам уведомление, оставайтесь на связи!',
        ]);
    }
}
