<?php

chdir(__DIR__ . '/../');

require 'vendor/autoload.php';

$config = include 'config/config.php';

$password = 'Secret01';
$hash = password_hash($password, PASSWORD_BCRYPT, $config['password']);

echo $hash . "\n";
