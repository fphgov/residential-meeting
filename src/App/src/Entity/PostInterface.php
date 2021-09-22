<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\Collection;

interface PostInterface extends EntityInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'id',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public const STATUS_PUBLISH = 1;
    public const STATUS_FUTURE  = 2;
    public const STATUS_DRAFT   = 3;
    public const STATUS_PENDING = 4;
    public const STATUS_PRIVATE = 5;
    public const STATUS_TRASH   = 6;

    public const STATUSES = [
        self::STATUS_PUBLISH => 'Publikálva',
        self::STATUS_FUTURE  => 'Időzített',
        self::STATUS_DRAFT   => 'Vázlat',
        self::STATUS_PENDING => 'Függőben lévő',
        self::STATUS_PRIVATE => 'Privát',
        self::STATUS_TRASH   => 'Lomtárban',
    ];

    public function setAuthor(User $author): void;

    public function getAuthor(): User;

    public function setFeaturedImage(Media $featuredImage = null): void;

    public function getFeaturedImage(): ?Media;

    public function setStatus(string $status): void;

    public function getStatus(): string;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setContent(string $content): void;

    public function getContent(): string;
}
