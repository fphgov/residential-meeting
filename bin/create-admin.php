<?php

declare(strict_types=1);

opcache_invalidate(__FILE__, true);

if (PHP_SAPI !== 'cli') {
    return false;
}

$options = getopt("p:f:l:e:r:");

chdir(__DIR__ . '/../');

use App\Entity\User;
use App\Model\PBKDF2Password;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createUnsafeMutable(dirname(__DIR__));
$dotenv->load();

$container = require 'config/container.php';

$em = $container->get(EntityManagerInterface::class);

$passwordModel = new PBKDF2Password($options['p'], PBKDF2Password::PW_REPRESENTATION_CLEARTEXT);

$storablePassword = $passwordModel->getStorableRepresentation();

$date = new DateTime();

$user = new User();
$user->setFirstname($options['f'] ?? "Firstname");
$user->setLastname($options['l'] ?? "Lastname");
$user->setEmail($options['e']);
$user->setRole($options['r'] ?? "admin");
$user->setPassword($storablePassword);
$user->setActive(true);
$user->setCreatedAt($date);
$user->setUpdatedAt($date);

$em->persist($user);
$em->flush();
