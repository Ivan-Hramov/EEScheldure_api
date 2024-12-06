<?php

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