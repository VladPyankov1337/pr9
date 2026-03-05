<?php
session_start();

$SECRET_KEY = 'cAtwa1kKEy'; 

if (!isset($_POST['token'])) {
    header('HTTP/1.0 400 Bad Request');
    echo "No token";
    exit;
}

$token = $_POST['token'];
$tokenParts = explode('.', $token);

if (count($tokenParts) != 3) {
    echo "Invalid token format";
    exit;
}

$header_base64 = $tokenParts[0];
$payload_base64 = $tokenParts[1];
$provided_signature = $tokenParts[2];

$unsignedToken = $header_base64.'.'.$payload_base64;
$signature = hash_hmac('sha256', $unsignedToken, $SECRET_KEY, true);
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

if ($base64UrlSignature === $provided_signature) {
    $payloadJson = base64_decode(str_replace(['-', '_'], ['+', '/'], $payload_base64));
    $payloadData = json_decode($payloadJson);

    if (isset($payloadData->userId)) {
        $_SESSION['user'] = $payloadData->userId;
        $_SESSION['role'] = isset($payloadData->role) ? $payloadData->role : 'user';
        echo "OK";
    } else {
        echo "No userId in token";
    }
} else {
    header('HTTP/1.0 401 Unauthorized');
    echo "Signature Invalid";
}
?>