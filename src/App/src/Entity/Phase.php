<?php

declare(strict_types=1);

namespace App\Entity;

use App\Traits\EntityTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhaseRepository")
 * @ORM\Table(name="phases", indexes={
 *     @ORM\Index(name="search_idx", columns={"code", "title"})
 * })
 */
final class Phase implements PhaseInterface
{
    use EntityTrait;

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=false)
     *
     * @var Campaign
     */
    private $campaign;

    /**
     * @ORM\Column(name="code", type="string", nullable=false)
     *
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(name="title", type="string", nullable=false)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=false)
     *
     * @var string
     */
    private $description = '';

    /**
     * @ORM\Column(name="start", type="datetime", nullable=false)
     *
     * @var DateTime
     */
    private $start;

    /**
     * @ORM\Column(name="end", type="datetime", nullable=false)
     *
     * @var DateTime
     */
    private $end;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign): void
    {
        $this->campaign = $campaign;
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

    public function setStart(DateTime $start): void
    {
        $this->start = $start;
    }

    public function getStart(): DateTime
    {
        return $this->start;
    }

    public function setEnd(DateTime $end): void
    {
        $this->end = $end;
    }

    public function getEnd(): DateTime
    {
        return $this->end;
    }
}
