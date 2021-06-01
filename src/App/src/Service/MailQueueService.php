<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\MailQueue;
use Mail\MailAdapter;
use Laminas\Log\Logger;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
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

    public function add(MailAdapter $mailAdapter)
    {
        $date = new DateTime();

        $mailQueue = new MailQueue();
        $mailQueue->setMailAdapter($mailAdapter);
        $mailQueue->setCreatedAt($date);
        $mailQueue->setUpdatedAt($date);

        $this->em->persist($mailQueue);
        $this->em->flush();

        if (isset($this->config['app']['notification']['force'])) {
            $this->sendMail($mailQueue);
        }
    }

    public function process(): void
    {
        $mailQueueRepository = $this->em->getRepository(MailQueue::class);

        $limit = isset($this->config['app']['notification']['frequency']) ?
                       $this->config['app']['notification']['frequency'] : 20;

        $this->emails = $mailQueueRepository->findAll([], [], $limit);

        foreach ($this->emails as $email) {
            $this->sendMail($email);
        }
    }

    private function sendMail(MailQueue $mailQueue): void
    {
        $mailAdapter = $mailQueue->getMailAdapter();

        try {
            $mailAdapter->send();

            $this->em->remove($mailQueue);
            $this->em->flush();

            usleep(250000);
        } catch (Throwable $th) {
            $this->audit->err('Mail no sended from mail queue', [
                'extra' => $mailQueue->getId(),
            ]);
        }
    }
}
