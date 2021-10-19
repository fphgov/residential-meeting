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

class ProjectListDTO
{
    private int $id;
    private string $campaignTitle;
    private string $campaignThemeName;
    private string $campaignThemeRgb;
    private string $title;
    private string $description;
    private string $location;
    private string $statusCode;
    private string $statusTitle;
    private ?string $tagId;
    private ?string $tagName;

    public function __construct(
        int $id,
        string $campaignTitle,
        string $campaignThemeName,
        string $campaignThemeRgb,
        string $title,
        string $description,
        string $location,
        string $statusCode,
        string $statusTitle,
        ?string $tagId = null,
        ?string $tagName = null
    ) {
        $this->id                = $id;
        $this->campaignTitle     = $campaignTitle;
        $this->campaignThemeName = $campaignThemeName;
        $this->campaignThemeRgb  = $campaignThemeRgb;
        $this->title             = $title;
        $this->description       = $description;
        $this->location          = $location;
        $this->statusCode        = $statusCode;
        $this->statusTitle       = $statusTitle;
        $this->tagId             = $tagId;
        $this->tagName           = $tagName;
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

    public function getTags(): array
    {
        if ($this->tagId === null || $this->tagName === null) {
            return [];
        }

        $tagIds   = explode(',', $this->tagId);
        $tagNames = explode(',', $this->tagName);

        $tags = [];

        foreach ($tagIds as $ti => $tagId) {
            $tags[$ti] = [
                'id'   => (int) $tagId,
                'name' => $tagNames[$ti],
            ];
        }

        return $tags;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getLocation(): string
    {
        return $this->location;
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
