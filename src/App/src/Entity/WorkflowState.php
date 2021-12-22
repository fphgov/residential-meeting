<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkflowStateRepository")
 * @ORM\Table(name="workflow_states", indexes={
 *     @ORM\Index(name="search_idx", columns={"code"})
 * })
 */
class WorkflowState implements WorkflowStateInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     *
     * @Groups({"option"})
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="code", type="string")
     *
     * @Groups({"list", "detail", "full_detail", "option"})
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @Groups({"list", "detail", "full_detail", "option"})
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="private_title", type="string")
     *
     * @Groups({"full_detail", "option"})
     * @var string
     */
    private $privateTitle;

    /**
     * @ORM\Column(name="description", type="string")
     *
     * @Groups({"detail", "full_detail"})
     * @var string
     */
    private $description;

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

    public function setPrivateTitle(string $privateTitle): void
    {
        $this->privateTitle = $privateTitle;
    }

    public function getPrivateTitle(): string
    {
        return $this->privateTitle;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
