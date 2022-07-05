<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ResetPasswordHandler implements RequestHandlerInterface
{
    /** @var UserServiceInterface **/
    private $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody(); // TODO: filter

        try {
            $this->userService->resetPassword($body['hash'], $body['password']);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Ez a jelszó visszaállító kulcs érvénytelen vagy már nem aktív. Lehet időközben újat kértél?',
            ], 404);
        }

        return new JsonResponse([
            'message' => 'Sikeres jelszó beállítás',
        ]);
    }
}
