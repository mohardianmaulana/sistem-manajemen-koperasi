<?php

namespace Modules\Pinjaman\Http\Controllers\web;

use App\Models\Core\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $update = $request->all();

        if (!isset($update['message'])) {
            return response()->json(['ok' => true]);
        }

        $chatId = $update['message']['chat']['id'];

        $text = trim($update['message']['text'] ?? '');

        if (str_starts_with($text, '/start')) {

            $parts = explode(' ', $text);

            $token = $parts[1] ?? null;

            if (!$token) {

                $this->reply(
                    $chatId,
                    "Silakan hubungkan akun melalui sistem koperasi."
                );

                return response()->json();
            }

            $user = User::where('telegram_token', $token)->first();

            if (!$user) {

                $this->reply(
                    $chatId,
                    "Kode tidak valid atau sudah digunakan."
                );

                return response()->json();
            }

            $user->telegram_chat_id = $chatId;
            $user->telegram_token = null;
            $user->save();

            $this->reply(
                $chatId,
                "✅ Akun Telegram berhasil dihubungkan."
            );
        }

        return response()->json();
    }

    private function reply($chatId, $text)
    {
        Http::post(
            "https://api.telegram.org/bot".config('services.telegram.token')."/sendMessage",
            [
                'chat_id'=>$chatId,
                'text'=>$text
            ]
        );
    }
}