<?php

namespace App\Service;
use Telegram;

class TelegramBotService {

    public function __construct(Telegram $telegram){
        $this->telegram = $telegram;
        $this->type = $telegram->getUpdateType();
    }

}

?>
