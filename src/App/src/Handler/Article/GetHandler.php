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

final class GetHandler implements RequestHandlerInterface
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

        $post = $postRepository->findOneBy([
            'status' => ArticleStatusInterface::STATUS_PUBLISH,
            'slug'   => $request->getAttribute('slug'),
        ]);

        if ($post === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $normalizedArticle = $post->normalizer(null, ['groups' => 'detail']);

        return new JsonResponse([
            'data' => $normalizedArticle,
        ]);
    }
}
