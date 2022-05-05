<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityActiveTrait;
use App\Traits\EntityMetaTrait;
use App\Traits\EntityTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tags")
 */
class Tag implements TagInterface
{
    use EntityActiveTrait;
    use EntityMetaTrait;
    use EntityTrait;

    /**
     * @ORM\Column(name="name", type="string")
     *
     * @Groups({"list", "detail", "full_detail"})
     */
    private string $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
