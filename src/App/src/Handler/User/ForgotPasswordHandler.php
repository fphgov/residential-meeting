<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use App\Exception\UserNotActiveException;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Log\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

final class ForgotPasswordHandler implements RequestHandlerInterface
{
    private const RES_MESSAGE       = 'Amennyiben a rendszerünkben szerepel a fiók és ez aktív, úgy a megadott e-mailre kiküldtük a fiók emlékezetőt.';
    private const RES_ERROR_MESSAGE = 'Rendszerhiba. A problémát rögzítettük és próbáljuk a lehető legrövidebb időn belűl javítani.';

    /** @var UserServiceInterface **/
    private $userService;

    /** @var Logger */
    private $audit;

    public function __construct(
        UserServiceInterface $userService,
        Logger $audit
    ) {
        $this->userService = $userService;
        $this->audit       = $audit;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody(); // TODO: filter

        try {
            $this->userService->forgotPassword($body['email']);
        } catch (UserNotActiveException $e) {
            return new JsonResponse([
                'message' => self::RES_MESSAGE,
            ], 402);
        } catch (Exception $e) {
            $this->audit->err('Forgot account exception', [
                'extra' => $e->getMessage(),
            ]);

            return new JsonResponse([
                'message' => self::RES_ERROR_MESSAGE,
            ], 500);
        }

        return new JsonResponse([
            'message' => self::RES_MESSAGE,
        ]);
    }
}
