<?php

require_once 'connect.php';
include 'Telegram.php';

$telegram = new Telegram('5637021086:AAEGBKXf-KhpjA06oVdXgNpT1-2spJdN_hs');
$chat_id = $telegram->ChatID();
$text = $telegram->Text();

$admin_chat_id = 967469906;

date_default_timezone_set('Asia/Tashkent');

$data = $telegram->getData();
$message = $data['message'];
$name = $message['from']['first_name'];
$date = date('Y-m-d H:i:s', $message['date']);

$step = "";
$sql = "SELECT * FROM users where chat_id = '$chat_id' AND step != 'saved'";
$result = $connect->query($sql);
if ($result->num_rows != 0) {
    $sql = "SELECT step FROM users where chat_id = '$chat_id'AND step != 'saved'";
    $result = $connect->query($sql);
    $row = $result->fetch_assoc();
    $step = $row['step'];
}

//$message['contact']['phone_number'];
$orders = [
    "1kg - 50 000 so'm",
    "1.5kg(1L) - 75 000 so'm",
    "4.5kg(3L) - 220 000 so'm",
    "7.5kg(5L) - 370 000 so'm",
];

if ($text == "/start") {
    showStart();
}

switch ($step) {
    case "start":
        switch ($text) {
            case "/start":
                showStart();
                break;
            case "📜 Biz haqimizda":
                showAbout();
                break;
            case "🚛 Buyurtma berish":
                showOrder();
                break;
            default:
                alert();
        }
        break;
    case "order":
        if (in_array($text, $orders)) {
            $index = array_search($text, $orders);
            $sql = "UPDATE users SET step = 'phone', product = '$index' WHERE chat_id = '$chat_id' and step != 'saved'";
            $connect->query($sql);
            askContact();
        } elseif ($text == "🔙 Orqaga") {
            showStart();
        } else alert();
        break;
    case "phone":
        if ($text == "🔙 Orqaga") {
            showOrder();
        }
        if ($message['contact']['phone_number'] != "") {
            $phone = $message['contact']['phone_number'];
            $sql = "UPDATE users SET step = 'delivery', phone = '$phone' WHERE chat_id = '$chat_id' and step != 'saved'";
            $connect->query($sql);
            showDelivery();
        }
        break;
    case "delivery":
        switch ($text) {
            case "✈️Yetkazib berish ✈️" :
                askLocation();
                break;
            case "🍯️  O'zim borib olaman 🍯️":
                giveMe();
                break;
            case "🔙 Orqaga":
                $sql = "UPDATE users SET step = 'phone' WHERE chat_id = '$chat_id' and step != 'saved'";
                $connect->query($sql);
                askContact();
                break;
            default:
                alert();
        }
        break;
    case "location" :
        $latitude = $message['location']['latitude'];
        $longitude = $message['location']['longitude'];
        if ($text == "🔙 Orqaga"){
            $sql = "UPDATE users SET step = 'delivery' WHERE chat_id = '$chat_id' and step != 'saved'";
            $connect->query($sql);
            showDelivery();
        }
        if ($latitude != "" && $longitude != "") {
            $sql = "UPDATE users SET step = 'saved', latitude = '$latitude', longitude = '$longitude' WHERE chat_id = '$chat_id' and step != 'saved'";
            $connect->query($sql);
            giveMe();
        }
        break;
}

function showStart()
{
    global $telegram, $chat_id, $name, $date, $connect;
    $sql = "SELECT * from users WHERE chat_id='$chat_id' and step != 'saved'";
    $result = $connect->query($sql);
    if ($result->num_rows == 0) {
        $sql = "insert into users (chat_id,name,created_at,step) values ('$chat_id','$name','$date','start')";
        $connect->query($sql);
    } else {
        $sql = "UPDATE users SET step = 'start' WHERE chat_id = '$chat_id' and step != 'saved'";
        $connect->query($sql);
    }
    $option = array(
        array($telegram->buildKeyboardButton("📜 Biz haqimizda")),
        array($telegram->buildKeyboardButton("🚛 Buyurtma berish")),
    );
    $keyboard = $telegram->buildKeyBoard($option, true, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "Assalomu alaykum $name, Botimizga xush kelibsiz !  Bot orqali masofadan turib 🍯 asal buyurtma qilishingiz mumkin !"
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
    global $telegram, $chat_id, $connect, $orders;
    $sql = "UPDATE users SET step = 'order' WHERE chat_id = '$chat_id' and step != 'saved'";
    $connect->query($sql);
    $option = array(
        array($telegram->buildKeyboardButton($orders[0])),
        array($telegram->buildKeyboardButton($orders[1])),
        array($telegram->buildKeyboardButton($orders[2])),
        array($telegram->buildKeyboardButton($orders[3])),
        array($telegram->buildKeyboardButton("🔙 Orqaga")),
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
        array($telegram->buildKeyboardButton("🔙 Orqaga")),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "Hajm tanlandi. Endi telefon raqamingizni jo'nating.",
    ];
    $telegram->sendMessage($content);
}

function showDelivery()
{
    global $telegram, $chat_id;
    $option = array(
        array($telegram->buildKeyboardButton("✈️Yetkazib berish ✈️")),
        array($telegram->buildKeyboardButton("🍯️  O'zim borib olaman 🍯️")),
        array($telegram->buildKeyboardButton("🔙 Orqaga")),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);

    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "Manzilimiz hali yo'q",
    ];
    $telegram->sendMessage($content);
}

function askLocation()
{
    global $telegram, $chat_id, $connect;
    $sql = "UPDATE users SET step = 'location' WHERE chat_id = '$chat_id' and step != 'saved'";
    $connect->query($sql);
    $option = array(
        array($telegram->buildKeyboardButton("Manzilni jo'natish", false, true)),
        array($telegram->buildKeyboardButton("🔙 Orqaga")),
    );
    $keyboard = $telegram->buildKeyBoard($option, false, true);
    $content = [
        'chat_id' => $chat_id,
        'reply_markup' => $keyboard,
        'text' => "Endi manzilingizni jo'nating",
    ];
    $telegram->sendMessage($content);
}

function giveMe()
{
    global $telegram, $chat_id, $connect;
    $sql = "UPDATE users SET step = 'saved' WHERE chat_id = '$chat_id'  and step != 'saved'";
    $connect->query($sql);

    $content = [
        'chat_id' => $chat_id,
        'text' => "Buyurtma qabul qilindi. Siz bilan bog'lanamiz",
    ];
    $telegram->sendMessage($content);
}

function alert()
{
    global $telegram, $chat_id;
    $content = [
        'chat_id' => $chat_id,
        'text' => "⚠️ Bunday buyruq mavjud emas ! \nIltimos quyidagi tugmalardan birini tanlang 👇",
    ];
    $telegram->sendMessage($content);
}

?>