<?php

namespace App\Conversation\Flows;

class Welcome extends AbstractFlow
{
    protected $triggers = [
        '/start'
    ];

    protected $states = [
        'first'
    ];

    public function first()
    {
        $this->telegram()->sendMessage([
            'chat_id' => $this->user->user_telegram_id,
            'text' => 'Это Клон. Для продолжения работы с ботом вам необходимо ознакомиться и принять условия "Политики конфиденциальности". Если вы согласны с условиями, то нажмите кнопку "Принять"'
        ]);
    }
}
