<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

final class ForgotAccountHandler implements RequestHandlerInterface
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
            $this->userService->forgotAccount($body['email']);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Ismeretlen aktiváló kulcs',
            ], 404);
        }

        return new JsonResponse([
            'message' => 'Sikeres aktiválás',
        ]);
    }
}
