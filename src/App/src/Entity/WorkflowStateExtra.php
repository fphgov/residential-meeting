<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkflowStateExtraRepository")
 * @ORM\Table(name="workflow_state_extras", indexes={
 *     @ORM\Index(name="workflow_state_search_idx", columns={"code"})
 * })
 */
class WorkflowStateExtra implements WorkflowStateExtraInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     *
     * @Groups({"option"})
     */
    private int $id;

    /**
     * @ORM\Column(name="code", type="string")
     *
     * @Groups({"list", "detail", "full_detail", "option"})
     */
    private string $code;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @Groups({"list", "detail", "full_detail", "option"})
     */
    private string $title;

    /**
     * @ORM\Column(name="description", type="string")
     *
     * @Groups({"detail", "full_detail"})
     */
    private string $description;

    /** @ORM\Column(name="email_text", type="text") */
    private string $emailText;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setEmailText(string $emailText): void
    {
        $this->emailText = $emailText;
    }

    public function getEmailText(): string
    {
        return $this->emailText;
    }
}
