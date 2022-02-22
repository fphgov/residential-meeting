<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityActiveInterface;
use App\Interfaces\EntityInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

interface ImplementationInterface extends EntityInterface, EntityActiveInterface
{
    public const DISABLE_SHOW_DEFAULT = [
        'createdAt',
        'updatedAt',
    ];

    public const DISABLE_DEFAULT_SET = [];

    public function setContent(string $content): void;

    public function getContent(): string;

    public function getSubmitter(): UserInterface;

    public function setSubmitter(UserInterface $submitter): void;

    public function getProject(): Project;

    public function setProject(Project $project): void;

    public function getMedias(): array;

    public function getMediaCollection(): Collection;

    public function addMedia(MediaInterface $media): self;

    public function removeMedia(MediaInterface $media): self;
}
