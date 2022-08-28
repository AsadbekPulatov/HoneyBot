<?php

include 'Telegram.php';
$telegram = new Telegram('5637021086:AAEGBKXf-KhpjA06oVdXgNpT1-2spJdN_hs');
$chat_id = $telegram->ChatID();
$text = $telegram->Text();

$data = $telegram->getData();
$message = $data['message'];
$name = $message['from']['first_name'];

$orders = [
    "1kg - 50 000 so'm",
    "1.5kg(1L) - 75 000 so'm",
    "4.5kg(3L) - 220 000 so'm",
    "7.5kg(5L) - 370 000 so'm",
];

switch ($text) {
    case "/start":
        showStart();
        break;
    case "🍯 Ba'tafsil ma'lumot":
        showAbout();
        break;
    case "🍯 Buyurtma berish":
        showOrder();
        break;
    default:
        if (in_array($text, $orders)) {
            askContact();
        }
        break;
}

function showStart()
{
    global $telegram, $chat_id, $name;
    $option = array(
        array($telegram->buildKeyboardButton("🍯 Ba'tafsil ma'lumot")),
        array($telegram->buildKeyboardButton("🍯 Buyurtma berish")),
    );
    $keyboard = $telegram->buildKeyBoard($option, true, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "Assalomu alaykum '$name', Botimizga xush kelibsiz !  Bot orqali masofadan turib 🍯 asal buyurtma qilishingiz mumkin !"
    ];
    $telegram->sendMessage($content);
}

function showAbout()
{
    global $telegram, $chat_id;
    $content = [
        'chat_id' => $chat_id,
        'text' => "Biz haqimizda ma'lumot. <a href='https://telegra.ph/Biz-haqimizda-08-27-2'>Ko'rish</a>",
        'parse_mode' => 'html',
    ];
    $telegram->sendMessage($content);
}

function showOrder()
{
    global $telegram, $chat_id;
    $option = array(
        array($telegram->buildKeyboardButton("1kg - 50 000 so'm")),
        array($telegram->buildKeyboardButton("1.5kg(1L) - 75 000 so'm")),
        array($telegram->buildKeyboardButton("4.5kg(3L) - 220 000 so'm")),
        array($telegram->buildKeyboardButton("7.5kg(5L) - 370 000 so'm")),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "Buyurtma berish uchun hajmlardan birini tanlang.",
    ];
    $telegram->sendMessage($content);
}

function askContact()
{
    global $telegram, $chat_id;
    $option = array(
        array($telegram->buildKeyboardButton("Raqamni jo'natish", true, false)),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "Hajm tanlandi. Endi telefon raqamingizni jo'nating.",
    ];
    $telegram->sendMessage($content);
}

?>