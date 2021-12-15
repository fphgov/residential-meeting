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

    public function setAuthor(User $author): void;

    public function getAuthor(): User;

    public function setFeaturedImage(?Media $featuredImage = null): void;

    public function getFeaturedImage(): ?Media;

    public function setStatus(PostStatus $status): void;

    public function getStatus(): PostStatus;

    public function setCategory(PostCategory $category): void;

    public function getCategory(): PostCategory;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setContent(string $content): void;

    public function getContent(): string;
}
