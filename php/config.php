<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$config = [
  'client_id'     => $_ENV['CLIENT_ID'],
  'client_secret' => $_ENV['CLIENT_SECRET'],
  'redirect_uri'  => $_ENV['REDIRECT_URI'],
];
