<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;

interface ForgotDistrictInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getDate(): DateTime;

    public function setDate(DateTime $date): void;
}
