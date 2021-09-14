<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WorkflowStateRepository")
 * @ORM\Table(name="workflow_states", indexes={
 *     @ORM\Index(name="search_idx", columns={"code"})
 * })
 */
class WorkflowState implements JsonSerializable, WorkflowStateInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="code", type="string")
     *
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(name="title", type="string")
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="string")
     *
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

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
