<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\IllegalArgumentException;

use function count;
use function strpos;
use function substr;
use function intval;
use function implode;
use function hex2bin;
use function bin2hex;
use function hash_pbkdf2;
use function preg_match_all;

final class PBKDF2Password implements PasswordModelInterface
{
    public const PROCESS   = 'PBKDF2WITHHMACSHA512';
    public const ALGORITHM = 'sha512';

    private $iterations = 40000;
    private $salt;
    private $hash;

    public function __construct(string $password, int $representation = self::PW_REPRESENTATION_CLEARTEXT)
    {
        if ($representation === self::PW_REPRESENTATION_CLEARTEXT) {
            $this->encrypt($password);
        } else if ($representation === self::PW_REPRESENTATION_STORABLE) {
            $this->parsePassword($password);
        }
    }

    public function getStorableRepresentation(): string
    {
        return implode(":", [
            self::PROCESS,
            $this->iterations,
            $this->getSalt(),
            $this->getHash(),
        ]);
    }

    public function verify(string $clearTextPassword): bool
    {
        $salt = hex2bin($this->salt);

        $hash = hash_pbkdf2(self::ALGORITHM, $clearTextPassword, $salt, $this->iterations, 256);

        return ($hash === $this->hash);
    }

    public function getIterations(): string
    {
        return $this->iterations;
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function getSalt(): string
    {
        return $this->salt;
    }

    private function encrypt(string $clearTextPassword)
    {
        $salt = openssl_random_pseudo_bytes(16);

        $this->hash = hash_pbkdf2(self::ALGORITHM, $clearTextPassword, $salt, $this->iterations, 256);

        $this->salt = bin2hex($salt);
    }

    private function parsePassword(string $storedPassword): void
    {
        $storageTypeSeparatorIndex = strpos($storedPassword, self::SEPARATOR);

        if ($storageTypeSeparatorIndex === false) {
            throw new IllegalArgumentException($storedPassword);
        }

        $storageType = substr($storedPassword, 0, $storageTypeSeparatorIndex);

        if ($storageType !== self::PROCESS) {
            throw new IllegalArgumentException($storageType);
        }

        $password = substr($storedPassword, $storageTypeSeparatorIndex + 1);

        preg_match_all(self::FORMAT, $password, $matches);

        if (! $matches || count($matches) !== 4 || count($matches[0]) === 0) {
            throw new IllegalArgumentException($password);
        }

        $this->iterations = intval($matches[1][0]);

        $this->salt = $matches[2][0];
        $this->hash = $matches[3][0];
    }
}
