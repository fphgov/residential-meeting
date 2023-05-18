<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Service\ForgotAccountServiceInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ForgotTokenCheckHandler implements RequestHandlerInterface
{
    public function __construct(
        private ForgotAccountServiceInterface $forgotAccountService,
        private InputFilterInterface $forgotTokenCheckFilter
    ) {
        $this->forgotAccountService   = $forgotAccountService;
        $this->forgotTokenCheckFilter = $forgotTokenCheckFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->forgotTokenCheckFilter->setData($body);

        if (! $this->forgotTokenCheckFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->forgotTokenCheckFilter->getMessages(),
            ], 422);
        }

        $isValidToken = $this->forgotAccountService->checkValidToken(
            $this->forgotTokenCheckFilter->getValue('token')
        );

        if (! $isValidToken) {
            return new JsonResponse([
                'error' => 'Érvénytelen token',
            ], 422);
        }

        return new JsonResponse([
            'message' => 'Sikeres token ellenőrzés',
        ]);
    }
}
