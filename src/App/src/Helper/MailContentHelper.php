<?php

declare(strict_types=1);

namespace App\Helper;

use App\Entity\Mail;
use Doctrine\ORM\EntityManagerInterface;
use Mail\Helper\MailContentHelperInterface;
use Mustache_Engine;

final class MailContentHelper implements MailContentHelperInterface
{
    /** @var EntityManagerInterface */
    private $em;

    private $mailRepository;

    /** @var string */
    private $code;

    /** @var array */
    private $variables;

    /** @var Mail */
    private $mail;

    public function __construct(
        EntityManagerInterface $em
    ) {
        $this->em             = $em;
        $this->mailRepository = $this->em->getRepository(Mail::class);
    }

    public function create(string $code, array $variables): self
    {
        $this->code      = $code;
        $this->variables = $variables;

        $this->mail = null;
        $this->mail = $this->mailRepository->findOneBy([
            'code' => $this->code,
        ]);

        return $this;
    }

    public function render(string $type): string
    {
        $mustache = new Mustache_Engine();

        return $mustache->render($this->mail->{'get' . $type}(), $this->variables);
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }
}
