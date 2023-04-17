<?php

declare(strict_types=1);

namespace App\Handler\Account;

use App\Service\AccountServiceInterface;
use App\Service\VoteServiceInterface;
use App\Exception\AccountNotVotableException;
use App\Exception\CloseCampaignException;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\InputFilter\InputFilterInterface;
use Laminas\Mail\Header\HeaderName;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function strtolower;

final class CheckHandler implements RequestHandlerInterface
{
    public function __construct(
        private AccountServiceInterface $accountService,
        private VoteServiceInterface $voteService,
        private InputFilterInterface $accountCheckFilter
    ) {
        $this->voteService        = $voteService;
        $this->accountService     = $accountService;
        $this->accountCheckFilter = $accountCheckFilter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $body = $request->getParsedBody();

        $this->accountCheckFilter->setData($body);

        if (! $this->accountCheckFilter->isValid()) {
            return new JsonResponse([
                'errors' => $this->accountCheckFilter->getMessages(),
            ], 422);
        }

        try {
            $account = $this->accountService->getAccount(
                $this->accountCheckFilter->getValues()['auth_code']
            );

            $this->voteService->checkVoteable($account);
        } catch (CloseCampaignException $e) {
            return new JsonResponse([
                'error' => 'A szavazás jelenleg zárva tart',
            ], 422);
        } catch (AccountNotVotableException $e) {
            return new JsonResponse([
                'error' => 'Már leadtad a szavazatod, nem szavazhatsz újra',
            ], 422);
        }

        if ($this->accountCheckFilter->getValues()['email']) {
            $email = strtolower($this->accountCheckFilter->getValues()['email']);

            try {
                HeaderName::assertValid($email);
            } catch (Exception $e) {
                return new JsonResponse([
                    'errors' => [
                        'email' => [
                            'format' => 'Nem megfelelő az e-mail cím formátuma. Kérjük ellenőrizze újra. Ékezetes betűk és a legtöbb speciális karakter nem elfogadható.',
                        ],
                    ],
                ], 422);
            }
        }

        return new JsonResponse([
            'message' => 'Sikeres hitelesítés',
        ]);
    }
}
