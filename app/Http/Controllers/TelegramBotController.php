<?php

namespace App\Http\Controllers;

use App\Service\TelegramBotService;
use Telegram;

class TelegramBotController extends Controller
{

    public function index(TelegramBotService $telegramBotService)
    {
        $token = config('service.telegram.token');
        $telegram = new Telegram($token);
        $data = new $telegramBotService($telegram);
    }
}
