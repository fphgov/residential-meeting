<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Entity\Article;
use App\Middleware\UserMiddleware;
use App\Service\ArticleServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AdminDeleteHandler implements RequestHandlerInterface
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var ArticleServiceInterface */
    protected $articleService;

    public function __construct(
        EntityManagerInterface $em,
        ArticleServiceInterface $articleService
    ) {
        $this->em          = $em;
        $this->articleService = $articleService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        $articleRepository = $this->em->getRepository(Article::class);

        $article = $articleRepository->find($request->getAttribute('id'));

        if ($article === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        try {
            $this->articleService->deleteArticle($user, $article);
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
