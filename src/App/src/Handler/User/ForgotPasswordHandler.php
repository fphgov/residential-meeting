<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use App\Exception\UserNotActiveException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

final class ForgotPasswordHandler implements RequestHandlerInterface
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
            $this->userService->forgotPassword($body['email']);
        } catch (UserNotActiveException $e) {
            return new JsonResponse([
                'message' => 'Amennyiben van a rendszerünkben ilyen fiók és aktív, úgy a megadott e-mailre kiküldtük a fiók emlékezetőt.',
            ], 402);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Ismeretlen hiba',
            ], 404);
        }

        return new JsonResponse([
            'message' => 'Sikeres jelszó emlékeztető',
        ]);
    }
}
