<?php

declare(strict_types=1);

namespace App\Model;

interface PasswordModelInterface
{
    public const PW_REPRESENTATION_CLEARTEXT = 0;
    public const PW_REPRESENTATION_STORABLE  = 1;

    public const PASSWORD_REPRESENTATIONS = [
        self::PW_REPRESENTATION_CLEARTEXT,
        self::PW_REPRESENTATION_STORABLE,
    ];

    public const FORMAT = '/(\d+):([a-z0-9]+):([a-z0-9]+)$/';

    public const SEPARATOR = ':';

    public function getStorableRepresentation(): string;

    public function verify(string $clearTextPassword): bool;

    public function getIterations(): string;

    public function getHash(): string;

    public function getSalt(): string;
}
