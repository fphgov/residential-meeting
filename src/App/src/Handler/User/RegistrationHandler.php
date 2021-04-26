<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use App\InputFilter\UserRegistrationFilter;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Mail\Header\HeaderName;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Exception;

use function strtolower;

final class RegistrationHandler implements RequestHandlerInterface
{
    /** @var UserServiceInterface **/
    private $userService;

    /** @var UserRegistrationFilter **/
    private $userRegistrationFilter;

    public function __construct(
        UserServiceInterface $userService,
        UserRegistrationFilter $userRegistrationFilter
    ) {
        $this->userService            = $userService;
        $this->userRegistrationFilter = $userRegistrationFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->userRegistrationFilter->setData($body);

        if (! $this->userRegistrationFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->userRegistrationFilter->getMessages(),
            ], 422);
        }

        $email = strtolower($this->userRegistrationFilter->getValues()['email']);

        try {
            HeaderName::assertValid($email);
        } catch (Exception $e) {
            return new JsonResponse([
                'errors' => [
                    'email' => [
                        'format' => 'Nem megfelelő e-mail cím. Kérjük ellenőrizze újra. Ékezetes betűk és a legtöbb speciális karakter nem elfogadható.',
                    ],
                ],
            ], 422);
        }

        try {
            $this->userService->registration($body);
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Sikertelen regisztráció',
            ], 400);
        }

        return new JsonResponse([
            'message' => 'Sikeres aktiválás',
        ]);
    }
}
