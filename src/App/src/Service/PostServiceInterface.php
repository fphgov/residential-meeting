<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\PostInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;

interface PostServiceInterface
{
    public function addPost(
        UserInterface $submitter,
        array $filteredParams
    ): void;

    public function modifyPost(
        PostInterface $post,
        array $filteredParams
    ): void;

    public function getRepository(): EntityRepository;
}
