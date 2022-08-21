<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;

interface ArticleInterface extends EntityInterface
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

    public function setStatus(ArticleStatus $status): void;

    public function getStatus(): ArticleStatus;

    public function setCategory(ArticleCategory $category): void;

    public function getCategory(): ArticleCategory;

    public function setTitle(string $title): void;

    public function getTitle(): string;

    public function setDescription(string $description): void;

    public function getDescription(): string;

    public function setContent(string $content): void;

    public function getContent(): string;
}
