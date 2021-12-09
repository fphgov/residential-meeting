<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntitySimpleInterface;

interface PostStatusInterface extends EntitySimpleInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'active',
        'createdAt',
        'updatedAt',
    ];

    public const STATUS_PUBLISH = 1;
    public const STATUS_DRAFT   = 2;
    public const STATUS_FUTURE  = 3;
    public const STATUS_PENDING = 4;
    public const STATUS_PRIVATE = 5;
    public const STATUS_TRASH   = 6;

    public const STATUSES = [
        self::STATUS_PUBLISH => 'Publikálva',
        self::STATUS_DRAFT   => 'Vázlat',
        self::STATUS_FUTURE  => 'Időzített',
        self::STATUS_PENDING => 'Függőben lévő',
        self::STATUS_PRIVATE => 'Privát',
        self::STATUS_TRASH   => 'Lomtárban',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function setName(string $name): void;

    public function getName(): string;
}
