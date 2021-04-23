<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Entity\UserPreference;
use App\Service\MailQueueServiceInterface;
use App\Model\PBKDF2Password;
use App\Exception\UserNotFoundException;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Mail\MailAdapter;
use Laminas\Log\Logger;
use Throwable;

use function error_log;

final class UserService implements UserServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Logger */
    private $audit;

    /** @var MailAdapter */
    private $mailAdapter;

    /** @var MailQueueServiceInterface */
    private $mailQueueService;

    /** @var EntityRepository */
    private $userRepository;

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        Logger $audit,
        MailAdapter $mailAdapter,
        MailQueueServiceInterface $mailQueueService
    ) {
        $this->config           = $config;
        $this->em               = $em;
        $this->audit            = $audit;
        $this->mailAdapter      = $mailAdapter;
        $this->mailQueueService = $mailQueueService;
        $this->userRepository   = $this->em->getRepository(User::class);
    }

    public function activate(string $hash): void
    {
        $user = $this->userRepository->findOneBy([
            'hash' => $hash
        ]);

        if (! ($user instanceof User)) {
            throw new UserNotFoundException($hash);
        }

        $user->setHash(null);
        $user->setActive(true);

        $this->em->flush();
    }

    public function addUser(array $filteredParams)
    {
        $date = new DateTime();

        $user           = new User();
        $userPreference = new UserPreference();
        $password       = new PBKDF2Password($filteredParams['password']);

        $userPreference->setUser($user);
        $userPreference->setAddress($filteredParams['address']);
        $userPreference->setBirthyear($filteredParams['birthyear']);
        $userPreference->setLiveInCity($filteredParams['liveInCity']);
        $userPreference->setPostalCode($filteredParams['postalCode']);
        $userPreference->setNickname($filteredParams['nickname']);
        $userPreference->setpolicy($filteredParams['policy']);
        $userPreference->setCreatedAt($date);
        $userPreference->setUpdatedAt($date);

        $user->setId(999);

        $user->setUserPreference($userPreference);
        $user->setHash($user->generateToken());
        $user->setUsername($filteredParams['username']);
        $user->setFirstname($filteredParams['firstname']);
        $user->setLastname($filteredParams['lastname']);
        $user->setEmail($filteredParams['email']);
        $user->setPassword($password->getStorableRepresentation());
        $user->setCreatedAt($date);
        $user->setUpdatedAt($date);

        $this->em->persist($userPreference);
        $this->em->persist($user);
        $this->em->flush();

        $this->newUserMail($user);

        return $user;
    }

    private function newUserMail(User $user)
    {
        $this->mailAdapter->clear();

        try {
            $this->mailAdapter->message->addTo($user->getEmail());
            $this->mailAdapter->message->setSubject('Erősítse meg a regisztrációját az Ötlet.budapest.hu-n');

            $tplData = [
                'name'             => $user->getFirstname(),
                'infoMunicipality' => $this->config['app']['municipality'],
                'infoEmail'        => $this->config['app']['email'],
                'activation'       => $this->config['app']['url'] . '/profil/aktivalas/' . $user->getHash(),
            ];

            $this->mailAdapter->setTemplate('email/user-created', $tplData);

            $this->mailQueueService->add($this->mailAdapter);
        } catch (Throwable $e) {
            error_log($e);

            $this->audit->err('New user notification no added to MailQueueService', [
                'extra' => $user->getId(),
            ]);
        }
    }

    public function getRepository(): EntityRepository
    {
        return $this->userRepository;
    }
}
