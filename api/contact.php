<?php
$name = $_POST['name'] ?? null;
$email = $_POST['email'] ?? null;
$message = $_POST['message'] ?? null;

if (!$name || !$email || !$message) {
    error_exit('Не переданы параметры запроса!', 400);
    return;
}

$htmlContent = "<html><body><h1>Message from $name<br>$email</h1><p>$message</p></body></html>";

$postData = array(
    'fromAddress' => 'no-reply@eescheldure.ru',
    'toAddress' => 'ivan.hramov@tptlive.ee',
    'content' => $htmlContent,
    'subject' => 'New message from ' . $name,
);

$response = sendZohoMail(json_encode($postData));

if ($response['status']['code'] !== 200) {
    error_exit('Ошибка отправки сообщения!', 500);
    return;
}

success_exit('Сообщение успешно отправлено!');