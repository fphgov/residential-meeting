<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatRepository")
 * @ORM\Table(name="stats")
 */
class Stat implements StatInterface
{
    use EntityTrait;

    /**
     * @ORM\Column(name="date", type="date")
     *
     * @Groups({"stat", "full_detail"})
     */
    private DateTime $date;

    /**
     * @ORM\Id
     * @ORM\Column(name="day", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"stat", "full_detail"})
     */
    private int $day;

    /**
     * @ORM\Column(name="count", type="integer")
     *
     * @Groups({"stat", "full_detail"})
     */
    private int $count;

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }

    public function getDay(): int
    {
        return $this->day;
    }

    public function setDay(int $day): void
    {
        $this->day = $day;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }
}
