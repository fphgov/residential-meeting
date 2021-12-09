<?php

declare(strict_types=1);

namespace App\Interfaces;

use DateTime;

interface EntityInterface extends EntitySimpleInterface
{
    public function getCreatedAt(): DateTime;

    public function setCreatedAt(DateTime $createdAt): void;

    public function getUpdatedAt(): DateTime;

    public function setUpdatedAt(DateTime $updatedAt): void;
}
