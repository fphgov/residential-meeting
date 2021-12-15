<?php

declare(strict_types=1);

namespace App\Handler\Post;

use App\Entity\Post;
use App\Middleware\UserMiddleware;
use App\Service\PostServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

final class AdminDeleteHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var PostServiceInterface */
    protected $postService;

    public function __construct(
        EntityManagerInterface $em,
        PostServiceInterface $postService
    ) {
        $this->em          = $em;
        $this->postService = $postService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        $postRepository = $this->em->getRepository(Post::class);

        $post = $postRepository->find($request->getAttribute('id'));

        if ($post === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        try {
            $this->postService->deletePost($user, $post);
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage(),
            ], 500);
        }

        return new JsonResponse([
            'data' => [
                'success' => true,
            ],
        ]);
    }
}
