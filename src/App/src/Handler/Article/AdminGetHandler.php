<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AdminGetHandler implements RequestHandlerInterface
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

        $post = $postRepository->find($request->getAttribute('id'));

        if ($post === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $normalizedArticle = $post->normalizer(null, ['groups' => 'full_detail']);

        return new JsonResponse([
            'data' => $normalizedArticle,
        ]);
    }
}
