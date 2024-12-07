<?php
$zohoToken = null;
$zohoTokenCreated = null;

function error_exit($message, $code = 0) {
    $array = array(
        "success" => false,
        "message" => $message
    );

    http_response_code($code);

    exit(json_encode($array, JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE));
}

function success_exit($data, $code = 200) {
    $array = array(
        "success" => true,
        "data" => $data
    );

    http_response_code($code);

    exit(json_encode($array, JSON_UNESCAPED_SLASHES + JSON_UNESCAPED_UNICODE));
}

function sendZohoMail($data) {
    global $zohoToken, $zohoTokenCreated;
    if (!$zohoToken || !$zohoTokenCreated || time() - $zohoTokenCreated > 3500) {
        $ch = curl_init('https://accounts.zoho.eu/oauth/v2/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'refresh_token' => $_ENV['ZOHO_REFRESH_TOKEN'],
            'client_id' => $_ENV['ZOHO_CLIENT_ID'],
            'client_secret' => $_ENV['ZOHO_CLIENT_SECRET'],
            'grant_type' => 'refresh_token'
        ]));

        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response, true);

        $zohoToken = $response['access_token'];
        $zohoTokenCreated = time();
    }

    $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
        'Authorization: Bearer ' . $zohoToken
    ];

    $ch = curl_init('https://mail.zoho.eu/api/accounts/6120866000000002002/messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}