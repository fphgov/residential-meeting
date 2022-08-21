<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Entity\Article;
use App\Entity\ArticleStatusInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function explode;
use function str_replace;

final class GetAllHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $postRepository = $this->entityManager->getRepository(Article::class);

        $queryParams = $request->getQueryParams();
        $limit       = $queryParams['limit'] ?? null;
        $category    = $queryParams['category'] ?? 1;

        $postCategories = explode(';', str_replace(',', ';', (string) $category));

        $posts = $postRepository->findBy([
            'status'   => ArticleStatusInterface::STATUS_PUBLISH,
            'category' => $postCategories,
        ], [
            'createdAt' => 'DESC',
        ], $limit);

        if ($posts === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $normalizedArticles = [];
        foreach ($posts as $post) {
            $normalizedArticles[] = $post->normalizer(null, ['groups' => 'list']);
        }

        return new JsonResponse([
            'data' => $normalizedArticles,
        ]);
    }
}
