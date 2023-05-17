<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ForgotDistrictRepository")
 * @ORM\Table(name="forgot_district")
 */
class ForgotDistrict implements ForgotDistrictInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="name", type="string", options={"unsigned"=true}, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @Groups({"option", "full_detail"})
     */
    private string $name;

    /**
     * @ORM\Column(name="date", type="datetime", nullable=false)
     *
     * @Groups({"option", "full_detail"})
     */
    private DateTime $date;


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function setDate(DateTime $date): void
    {
        $this->date = $date;
    }
}
