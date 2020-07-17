<?php

declare(strict_types=1);

namespace Jwt\Handler;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;
use function password_verify;

class TokenHandler implements RequestHandlerInterface
{
    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * @var Builder $builder
     */
    private $builder;

    /**
     * @var array config
     */
    private $config;

    public function __construct(EntityManagerInterface $em, Builder $builder, array $config)
    {
        $this->em      = $em;
        $this->builder = $builder;
        $this->config  = $config;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        $postBody = $request->getParsedBody();

        $userRepository = $this->em->getRepository(User::class);

        if (! isset($postBody['email']) || ! isset($postBody['password'])) {
            return $this->badAuthentication();
        }

        $user = $userRepository->findOneBy(['email' => $postBody['email']]);
        
        if (! $user) {
            return $this->badAuthentication();
        }

        if (! password_verify($postBody['password'], $user->getPassword())) {
            return $this->badAuthentication();
        }

        $userData = [
            'firstname' => $user->getFirstname(),
            'lastname'  => $user->getLastname(),
            'email'     => $user->getEmail(),
        ];

        $token = $this->generateToken($userData);

        return new JsonResponse([
            'token' => (string)$token,
        ], 200);
    }

    private function badAuthentication() {
        return new JsonResponse([
            'message' => 'Hibás bejelentkezési adatok',
        ], 400);
    }

    private function generateToken($claim = [])
    {
        $time   = time();
        $signer = new Sha256();
        $key    = new Key($this->config['auth']['secret']);

        return $this->builder
                    ->issuedBy($this->config['iss']) // Configures the issuer (iss claim)
                    ->permittedFor($this->config['aud']) // Configures the issuer (iss claim)
                    ->identifiedBy($this->config['jti'], true) // Configures the audience (aud claim)
                    ->issuedAt($time) // Configures the time that the token was issued (iat claim)
                    ->canOnlyBeUsedAfter($time + (int)$this->config['nbf']) // Configures the time that the token can be used (nbf claim)
                    ->expiresAt($time + (int)$this->config['exp']) // Configures the expiration time of the token (exp claim)
                    ->withClaim('user', $claim)
                    ->getToken($signer, $key); // Retrieves the generated token
    }
}
