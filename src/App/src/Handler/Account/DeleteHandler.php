<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Middleware\UserMiddleware;
use App\Model\PBKDF2Password;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function sprintf;

final class DeleteHandler implements RequestHandlerInterface
{
    private array $config;

    /** @var EntityManagerInterface */
    private $em;

    public function __construct(
        array $config,
        EntityManagerInterface $em
    ) {
        $this->config = $config;
        $this->em     = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $user = $request->getAttribute(UserMiddleware::class);

        if (! $user) {
            return new JsonResponse([
                'data' => [
                    'unsuccess' => 'No result',
                ],
            ], 404);
        }

        $user->setActive(false);

        $this->em->flush();

        return new JsonResponse([
            'message' => sprintf('A fiók törlésének kérését fogadtuk. A fiók %d órán belül törlödni fog.', $this->config['app']['account']['clearTimeHour']),
        ]);
    }
}
