<?php
require_once "vendor/autoload.php";
require_once "config.php";

$client = new Google_Client();
$client->setClientId($config["client_id"]);
$client->setClientSecret($config["client_secret"]);
$client->setRedirectUri($config["redirect_uri"]);
$client->addScope("https://www.googleapis.com/auth/youtube.readonly");
$client->setAccessType("offline");
$client->setPrompt("consent");

header("Location: " . $client->createAuthUrl());
exit();
