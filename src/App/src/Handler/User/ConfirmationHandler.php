<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ConfirmationHandler implements RequestHandlerInterface
{
    /** @var UserServiceInterface **/
    private $userService;

    /** @var InputFilterInterface **/
    private $voteFilter;

    public function __construct(
        UserServiceInterface $userService,
        InputFilterInterface $voteFilter
    )
    {
        $this->userService = $userService;
        $this->voteFilter  = $voteFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->voteFilter->setData($body);

        if (!$this->voteFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->voteFilter->getMessages(),
            ], 422);
        }

        try {
            $this->userService->confirmation($this->voteFilter->getValues(), $request->getAttribute('hash'));
        } catch (Exception $e) {
            return new JsonResponse([
                'message' => 'Ismeretlen aktíváló kulcs. Lehet nyilatkozott már a fiókja sorsáról?',
            ], 404);
        }

        return new JsonResponse([
            'message' => 'Sikeres aktiválás',
        ]);
    }
}
