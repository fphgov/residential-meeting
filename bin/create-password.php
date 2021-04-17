<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

$options = getopt("p:");

chdir(__DIR__ . '/../');

use App\Model\PBKDF2Password;

require 'vendor/autoload.php';

$passwordModel = new PBKDF2Password($options['p'], PBKDF2Password::PW_REPRESENTATION_CLEARTEXT);

$storablePassword = $passwordModel->getStorableRepresentation();

echo $storablePassword . "\n";
