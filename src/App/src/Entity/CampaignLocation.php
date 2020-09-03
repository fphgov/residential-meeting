<?php

declare(strict_types=1);

namespace App\Entity;

use App\Interfaces\EntityInterface;
use App\Traits\Entity;
use App\Traits\EntityMeta;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity
 * @ORM\Table(name="campaign_locations")
 */
class CampaignLocation implements JsonSerializable, CampaignLocationInterface
{
    const DISABLE_SHOW_DEFAULT = [
        'id',
        'updatedAt',
    ];

    use Entity;
    use EntityMeta;

    /**
     * @ORM\ManyToOne(targetEntity="Campaign")
     * @ORM\JoinColumn(name="campaign_id", referencedColumnName="id", nullable=true)
     * @var Campaign
     */
    private $campaign;

    /**
     * @ORM\Column(name="code", type="string")
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(name="name", type="string")
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="description", type="text")
     * @var string
     */
    private $description;

    public function getCampaign(): Campaign
    {
        return $this->campaign;
    }

    public function setCampaign(Campaign $campaign)
    {
        $this->campaign = $campaign;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code)
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
