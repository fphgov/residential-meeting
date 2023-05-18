<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Service\ForgotAccountServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ForgotFirstHandler implements RequestHandlerInterface
{
    public function __construct(
        private ForgotAccountServiceInterface $forgotAccountService,
        private InputFilterInterface $forgotCheckFilter
    ) {
        $this->forgotAccountService = $forgotAccountService;
        $this->forgotCheckFilter    = $forgotCheckFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->forgotCheckFilter->setData($body);

        if (! $this->forgotCheckFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->forgotCheckFilter->getMessages(),
            ], 422);
        }

        try {
            $this->forgotAccountService->generateToken(
                $this->forgotCheckFilter->getValue('email')
            );
        } catch (Exception $e) {
            return new JsonResponse([
                'error' => 'Hiba a feldolgozás közben, kérünk próbáld később',
            ], 422);
        }

        return new JsonResponse([
            'message' => 'Sikeres token igénylés',
        ]);
    }
}
