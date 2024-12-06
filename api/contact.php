<?php

$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$message = $_POST['message'] ?? null;

if (!$name || !$email || !$message) {
    error_exit('Не переданы параметры запроса!', 400);
    return;
}

$headers = [
    'Accept: application/json',
    'Content-Type: application/json',
    'Authorization: Bearer ' . $_ENV['ZOHO_TOKEN']
];

$htmlContent = "<html><body><h1>Message from $name<br>$email</h1><p>$message</p></body></html>";

$postData = array(
    'fromAddress' => 'no-reply@eescheldure.ru',
    'toAddress' => 'ivan.hramov@tptlive.ee',
    'content' => $htmlContent,
    'subject' => 'New message from ' . $name,
);

$postData = json_encode($postData);

$ch = curl_init('https://mail.zoho.eu/api/accounts/6120866000000002002/messages');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

if (str_contains($response, 'Вы не передали параметр')) {
    error_exit('Не переданы параметры запроса!', 400);
    return;
}

$response = json_decode($response, true);

if ($response['status']['code'] !== 200) {
    error_exit('Ошибка отправки сообщения!', 500);
    return;
}

success_exit('Сообщение успешно отправлено!');