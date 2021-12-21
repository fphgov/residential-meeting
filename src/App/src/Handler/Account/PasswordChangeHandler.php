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

final class PasswordChangeHandler implements RequestHandlerInterface
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
        $body = $request->getParsedBody();
        $user = $request->getAttribute(UserMiddleware::class);

        if (! $user) {
            return new JsonResponse([
                'data' => [
                    'unsuccess' => 'No result',
                ],
            ], 404);
        }

        if (empty($body['password']) || empty($body['password_again'])) {
            return new JsonResponse([
                'errors' => [
                    'password' => [
                        'required' => 'Kötelező a jelszó mezők kitöltése',
                    ],
                ],
            ], 422);
        }

        if ($body['password'] !== $body['password_again']) {
            return new JsonResponse([
                'errors' => [
                    'password_again' => [
                        'password_not_same' => 'Nem egyezik a megadott két jelszó',
                    ],
                ],
            ], 422);
        }

        $pass = new PBKDF2Password($body['password']);

        $user->setPassword($pass->getStorableRepresentation());

        $this->em->flush();

        return new JsonResponse([
            'message' => 'Sikeres jelszó módosítás',
        ]);
    }
}
