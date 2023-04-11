<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AccountInterface;
use App\Entity\MailLog;
use App\Entity\MailQueue;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Log\Logger;
use Mail\MailAdapterInterface;
use Throwable;

use function usleep;

final class MailQueueService implements MailQueueServiceInterface
{
    /** @var array */
    private $config;

    /** @var EntityManagerInterface */
    private $em;

    /** @var Logger */
    private $audit;

    /** @var array */
    private $emails = [];

    public function __construct(
        array $config,
        EntityManagerInterface $em,
        Logger $audit
    ) {
        $this->config = $config;
        $this->em     = $em;
        $this->audit  = $audit;
    }

    public function add(AccountInterface $account, MailAdapterInterface $mailAdapter): void
    {
        if (
            isset($this->config['app']['notification']['force']) &&
            $this->config['app']['notification']['force'] === true
        ) {
            $mailQueue = $this->createMailQueue($account, $mailAdapter);
            $mailQueue->setId(0);

            $this->sendMail($mailQueue);
        } else {
            $this->push($account, $mailAdapter);
        }
    }

    private function createMailQueue(AccountInterface $account, MailAdapterInterface $mailAdapter): MailQueue
    {
        $date = new DateTime();

        $mailQueue = new MailQueue();
        $mailQueue->setAccount($account);
        $mailQueue->setMailAdapter($mailAdapter);
        $mailQueue->setCreatedAt($date);
        $mailQueue->setUpdatedAt($date);

        return $mailQueue;
    }

    private function push(AccountInterface $account, MailAdapterInterface $mailAdapter): void
    {
        $mailQueue = $this->createMailQueue($account, $mailAdapter);

        $this->em->persist($mailQueue);
        $this->em->flush();
    }

    public function process(): void
    {
        $mailQueueRepository = $this->em->getRepository(MailQueue::class);

        $limit = $this->config['app']['notification']['frequency'] ?? 20;

        $this->emails = $mailQueueRepository->findBy([], [], $limit);

        foreach ($this->emails as $email) {
            $this->sendMail($email);
        }
    }

    private function sendMail(MailQueue $mailQueue): void
    {
        $mailAdapter = $mailQueue->getMailAdapter();

        try {
            $this->createMailLog($mailQueue->getAccount(), $mailAdapter);

            $mailAdapter->send();

            if ($mailQueue->getId() !== 0) {
                $this->em->remove($mailQueue);
            }

            $this->em->flush();

            usleep(250000); // 0.25 sec
        } catch (Throwable $th) {
            $this->audit->err('Mail no sended from mail queue', [
                'extra' => $mailQueue->getId() . ' ' . $th->getMessage(),
            ]);
        }
    }

    private function createMailLog(AccountInterface $account, MailAdapterInterface $mailAdapter): void
    {
        $date = new DateTime();

        $mailLog = new MailLog();
        $mailLog->setAccount($account);
        $mailLog->setName($mailAdapter->getName());
        $mailLog->setMessageId($mailAdapter->getMessageId());
        $mailLog->setCreatedAt($date);
        $mailLog->setUpdatedAt($date);

        $this->em->persist($mailLog);
    }
}
