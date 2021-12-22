<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PostCategoryRepository")
 * @ORM\Table(name="post_categories")
 */
class PostCategory implements PostCategoryInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"list", "detail", "full_detail"})
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(name="code", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(name="name", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     * @var string
     */
    private $name;

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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
