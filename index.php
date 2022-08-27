<?php

include 'Telegram.php';
$telegram = new Telegram('5637021086:AAEGBKXf-KhpjA06oVdXgNpT1-2spJdN_hs');
$chat_id = $telegram->ChatID();
$text = $telegram->Text();

$data = $telegram->getData();
$message = $data['message'];
$name = $message['from']['first_name'];

if ($text == '/start') {
    $option = array(
        array($telegram->buildKeyboardButton("🍯 Ba'tafsil ma'lumot")),
        array($telegram->buildKeyboardButton("🍯 Buyurtma berish")),
    );
    $keyboard = $telegram->buildKeyBoard($option, true, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup'=>$keyboard,
        'text' => "Assalomu alaykum '$name', Botimizga xush kelibsiz !  Bot orqali masofadan turib 🍯 asal buyurtma qilishingiz mumkin !"
    ];
    $telegram->sendMessage($content);
}

?>