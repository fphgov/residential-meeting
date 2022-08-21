<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Entity\ArticleCategory;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class GetCategoryHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postCategoriesRepository = $this->em->getRepository(ArticleCategory::class);

        $normalizedCategories = [];
        foreach ($postCategoriesRepository->findAll() as $postCategory) {
            $normalizedCategories[] = $postCategory->normalizer(null, ['groups' => 'list']);
        }

        return new JsonResponse([
            'data' => $normalizedCategories,
        ]);
    }
}
