<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Entity\Post;
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
        $postRepository = $this->entityManager->getRepository(Post::class);

        $post = $postRepository->findOneBy([
            'status' => 'publish',
            'slug'   => $request->getAttribute('slug')
        ]);

        if ($post === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        $normalizedPost = $post->normalizer(null, ['groups' => 'detail']);

        return new JsonResponse([
            'data' => $normalizedPost,
        ]);
    }
}
