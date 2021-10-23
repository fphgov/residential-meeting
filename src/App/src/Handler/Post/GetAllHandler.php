<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

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
        $postRepository = $this->entityManager->getRepository(Post::class);

        $queryParams = $request->getQueryParams();
        $limit       = $queryParams['limit'] ?? null;
        $category    = $queryParams['category'] ?? 1;

        $posts = $postRepository->findBy([
            'status'   => 'publish',
            'category' => $category,
        ], [
            'createdAt' => 'DESC'
        ], $limit);

        if ($posts === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $normalizedPosts = [];
        foreach ($posts as $post) {
            $normalizedPosts[] = $post->normalizer(null, ['groups' => 'list']);
        }

        return new JsonResponse([
            'data' => $normalizedPosts,
        ]);
    }
}
