<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Model\PBKDF2Password;
use App\Middleware\UserMiddleware;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Router\RouteResult;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function password_hash;

final class PasswordChangeHandler implements RequestHandlerInterface
{
    /** @var array */
    private $config;

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
        $body = $request->getParsedBody();
        $user = $request->getAttribute(UserMiddleware::class);

        if (! $user) {
            return new JsonResponse([
                'data' => [
                    'unsuccess' => 'No result',
                ],
            ], 404);
        }

        if (! empty($body['password'])) {
            $pass = new PBKDF2Password($body['password']);

            $user->setPassword($pass->getStorableRepresentation());
        }

        $this->em->flush();

        return new JsonResponse([
            'message' => 'Sikeres jelszó módosítás',
        ]);
    }
}
