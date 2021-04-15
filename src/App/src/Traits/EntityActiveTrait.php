<?php

declare(strict_types=1);

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EntityActiveTrait
{
    /**
     * @ORM\Column(name="active", type="boolean")
     *
     * @var bool
     */
    protected $active = false;

    public function getActive(): bool
    {
        return (bool) $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
