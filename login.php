<?php

// Hataları (varsa) Göster
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Session Başlat
session_start();

// Telegram Bot Token - Bot Token almak için, Telegramdan @botfather'dan bot oluşturun.
$botToken = "BOT_TOKEN";

$type = $_POST["type"];

// Eğer Giriş Yapılmışsa
if ($type == "login") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $tguser = $_POST['telegram_username'];
    $otpcode = rand(10000000, 99999999);
    $_SESSION["otp"] = $otpcode;

    function sendMessage($chatId, $message, $botToken) {
        $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage";
        $postData = http_build_query([
            'chat_id' => $chatId,
            'text' => $message
        ]);

        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => $postData
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === FALSE) {
            // Handle error
            return false;
        } else {
            return true;
        }
    }

    function checkMessageSent($tguser, $botToken) {
        $url = "https://api.telegram.org/bot" . $botToken . "/getUpdates";
        $data = file_get_contents($url);
        $updates = json_decode($data, true);

        foreach ($updates['result'] as $update) {
            if (isset($update['message']['chat']['username']) && $update['message']['chat']['username'] == $tguser) {
                return $update['message']['chat']['id'];
            }
        }
        return false;
    }

    $chatId = checkMessageSent($tguser, $botToken);

    if ($chatId) {
        $message = "Merhaba sayın $username\nGüvenlik kodunuz: $otpcode";
        $success = sendMessage($chatId, $message, $botToken);
        echo json_encode(['success' => $success]);
    } else {
        echo json_encode(['success' => false]);
    }
}

// Eğer OTP Ekranı Post Edilmişse
if ($type == "otp") {
    if ($_SESSION["otp"] == $_POST["otp"]) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
