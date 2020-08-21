<?php

require_once (__DIR__ . '\vendor\autoload.php');


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dropboxKey = $_ENV['DROPBOX_KEY'];
$dropboxSecret = $_ENV['DROPBOX_SECRET'];
$dropboxToken = $_ENV['DROPBOX_TOKEN'];

define('URL', 'http://localhost/mvc_php/versatil/');

define('DROPBOX_KEY', $dropboxKey);
define('DROPBOX_SECRET', $dropboxSecret);
define('DROPBOX_TOKEN', $dropboxToken);