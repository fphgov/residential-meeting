<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ArticleInterface;
use App\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;

interface ArticleServiceInterface
{
    public function addArticle(
        UserInterface $submitter,
        array $filteredParams
    ): void;

    public function modifyArticle(
        ArticleInterface $post,
        array $filteredParams
    ): void;

    public function getRepository(): EntityRepository;
}
