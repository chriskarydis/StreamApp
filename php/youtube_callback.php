<?php
session_start();
require_once "vendor/autoload.php";
require_once "config.php";

$client = new Google_Client();
$client->setClientId($config["client_id"]);
$client->setClientSecret($config["client_secret"]);
$client->setRedirectUri($config["redirect_uri"]);

if (!isset($_GET["code"])) {
    exit("No code returned");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

if (!isset($token["access_token"])) {
    exit("Failed to retrieve access token.");
}

$_SESSION["youtube_token"] = $token["access_token"];
header("Location: youtube_search.php");
exit();
