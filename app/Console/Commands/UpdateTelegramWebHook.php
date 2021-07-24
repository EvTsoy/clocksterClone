<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateTelegramWebHook extends Command
{
    protected $signature = 'telegram:webhook:update';

    protected $description = 'Refresh webhook data';

    public function handle()
    {
        $url = str_replace('http://', 'https://', route('telegram.webhook'));
        $result = \Telegram::bot()->setWebhook([
            'url' => $url
        ]);

        if(!$result) $this->error('Webhook not installed');
        $this->info('Webhook successfully installed');
    }
}
