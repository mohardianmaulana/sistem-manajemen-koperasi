<?php

namespace Modules\Pinjaman\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function sendMessage($chatId, $message)
    {
        if (!$chatId) {
            return false;
        }

        return Http::post(
            "https://api.telegram.org/bot".config('services.telegram.token')."/sendMessage",
            [
                'chat_id' => $chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]
        );
    }
}