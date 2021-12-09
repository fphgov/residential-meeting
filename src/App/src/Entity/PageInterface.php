<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface PageInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'id',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function setStatus(string $status): void;

    public function getStatus(): string;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setContent(string $content): void;

    public function getContent(): string;
}
