<?php

declare(strict_types=1);

namespace App\Handler\Article;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function explode;
use function str_replace;

final class AdminListHandler implements RequestHandlerInterface
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
        $postRepository = $this->em->getRepository(Article::class);

        $queryParams = $request->getQueryParams();
        $limit       = $queryParams['limit'] ?? null;
        $category    = $queryParams['category'] ?? null;

        $postCategories = explode(';', str_replace(',', ';', (string) $category));

        $queryData = [];

        if ($category !== null) {
            $queryData[] = $postCategories;
        }

        $posts = $postRepository->findBy($queryData, [
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
