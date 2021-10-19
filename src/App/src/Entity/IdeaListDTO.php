<?php

declare(strict_types=1);

namespace App\Entity;

use function array_slice;
use function count;
use function explode;
use function implode;
use function min;
use function strip_tags;
use function trim;

class IdeaListDTO
{
    private int $id;
    private string $campaignTitle;
    private string $campaignThemeName;
    private string $campaignThemeRgb;
    private string $title;
    private string $description;
    private string $statusCode;
    private string $statusTitle;
    private $campaignLocation;

    public function __construct(
        int $id,
        string $campaignTitle,
        string $campaignThemeName,
        string $campaignThemeRgb,
        string $title,
        string $description,
        string $statusCode,
        string $statusTitle,
        $campaignLocation
    ) {
        $this->id                = $id;
        $this->campaignTitle     = $campaignTitle;
        $this->campaignThemeName = $campaignThemeName;
        $this->campaignThemeRgb  = $campaignThemeRgb;
        $this->title             = $title;
        $this->description       = $description;
        $this->statusCode        = $statusCode;
        $this->statusTitle       = $statusTitle;
        $this->campaignLocation  = $campaignLocation;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): array
    {
        return [
            'code'  => $this->statusCode,
            'title' => $this->statusTitle,
        ];
    }

    public function getCampaignTheme(): array
    {
        return [
            'title' => $this->campaignTitle,
            'name'  => $this->campaignThemeName,
            'rgb'   => $this->campaignThemeRgb,
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLocation(): ?string
    {
        return $this->campaignLocation;
    }

    public function getDescription(): string
    {
        $description = $this->description;

        $description = strip_tags($description);

        $descriptions = explode(" ", $description);
        $descriptions = array_slice($descriptions, 0, min(22, count($descriptions) - 1));

        $description  = trim(implode(" ", $descriptions));
        $description .= ' ...';

        return $description;
    }
}
