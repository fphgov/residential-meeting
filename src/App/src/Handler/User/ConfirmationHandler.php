<?php

declare(strict_types=1);

namespace App\Handler\User;

use App\Service\UserServiceInterface;
use App\Repository\UserRepository;
use App\Exception\UserNotFoundException;
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

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        UserServiceInterface $userService,
        InputFilterInterface $voteFilter,
        UserRepository $userRepository
    )
    {
        $this->userService    = $userService;
        $this->voteFilter     = $voteFilter;
        $this->userRepository = $userRepository;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        try {
            $user = $this->userRepository->getUserByHash($request->getAttribute('hash'));
        } catch (UserNotFoundException $e) {
            return new JsonResponse([
                'message' => 'Ismeretlen aktíváló kulcs. Lehet nyilatkoztál már a fiókod sorsáról?',
            ], 404);
        }

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
                'message' => 'Ismeretlen aktíváló kulcs. Lehet nyilatkoztál már a fiókod sorsáról?',
            ], 404);
        }

        if ($this->voteFilter->getValue('profile_save') === 'true' && $this->voteFilter->getValue('newsletter') === 'true') {
            return new JsonResponse([
                'message' => 'Köszönjük, hogy megerősítetted a(z) ' . $user->getEmail() . ' címhez tartozó felhasználói fiókod és örömmel vettük feliratkozásodat hírlevelünkre. Hamarosan értesítünk a harmadik fővárosi közösségi költségvetés indulásáról.',
            ]);
        } else if ($this->voteFilter->getValue('profile_save') === 'true') {
            return new JsonResponse([
                'message' => 'Köszönjük, hogy megerősítetted a(z) ' . $user->getEmail() . ' címhez tartozó felhasználói fiókod. Hamarosan értesítünk a harmadik fővárosi közösségi költségvetés indulásáról.',
            ]);
        } else if ($this->voteFilter->getValue('newsletter') === 'true') {
            return new JsonResponse([
                'message' => 'Örömmel vettük feliratkozásodat hírlevelünkre. Hamarosan értesítünk a harmadik fővárosi közösségi költségvetés indulásáról.',
            ]);
        }

        return new JsonResponse([
            'message' => 'Sikeres aktiválás',
        ]);
    }
}
