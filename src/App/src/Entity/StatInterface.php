<?php

declare(strict_types=1);

namespace App\Entity;

use DateTime;

interface StatInterface
{
    public function getDate(): DateTime;

    public function setDate(DateTime $date): void;

    public function getDay(): int;

    public function setDay(int $day): void;

    public function getCount(): int;

    public function setCount(int $count): void;
}
