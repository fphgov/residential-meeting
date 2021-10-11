<?php

declare(strict_types=1);

namespace App\Handler\Page;

use App\Entity\Page;
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
        $pageRepository = $this->entityManager->getRepository(Page::class);

        $result = $pageRepository->findOneBy([
            'slug' => $request->getAttribute('slug')
        ]);

        if ($result === null) {
            return new JsonResponse([
                'errors' => 'Nem található',
            ], 404);
        }

        return new JsonResponse([
            'data' => $result,
        ]);
    }
}
