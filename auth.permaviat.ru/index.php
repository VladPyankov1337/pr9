<?php
require_once("config.php");
require_once("settings/connect.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Expose-Headers: token");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$SECRET_KEY = 'cAtwa1kKEy';

if(!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER'])) { header('HTTP/1.0 403 Forbidden'); exit; }
if(!isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) { header('HTTP/1.0 403 Forbidden'); exit; }

$login = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password';");

if($read_user = $query_user->fetch_assoc()) {
    $header = array("typ" => "JWT", "alg" => "sha256");

    $payload = array("userId" => password_hash($read_user['id'], PASSWORD_DEFAULT));

    $header_base64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
    $payload_base64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));

    $unsignedToken = $header_base64.'.'.$payload_base64;
    $signature = hash_hmac($header['alg'], $unsignedToken, $SECRET_KEY, true);

    $token = $unsignedToken.".".str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    header("token: ".$token);
} else
    header('HTTP/1.0 401 Unauthorized');
?>