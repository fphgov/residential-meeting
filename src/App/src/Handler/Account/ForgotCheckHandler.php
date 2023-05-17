<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Service\ForgotAccountServiceInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ForgotCheckHandler implements RequestHandlerInterface
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

        $isAvailableProcess = $this->forgotAccountService->checkAvailable(
            $this->forgotCheckFilter->getValue('district')
        );

        if (! $isAvailableProcess) {
            return new JsonResponse([
                'error' => 'A kerületedben még nem lehet egyedi azonosítót igényelni, kérjük, térj vissza a terjesztési időszak végén.',
            ], 422);
        }

        return new JsonResponse([
            'message' => 'Sikeres kerület ellenőrzés',
        ]);
    }
}
