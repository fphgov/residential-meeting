<?php

declare(strict_types=1);

namespace App\Entity;

use function array_slice;
use function count;
use function explode;
use function min;
use function implode;
use function strip_tags;
use function trim;

class ProjectListDTO
{
    private $id;
    private $campaignThemeName;
    private $campaignThemeRgb;
    private $title;
    private $description;
    private $location;
    private $tagId;
    private $tagName;

    public function __construct(
        int $id,
        $campaignThemeName,
        $campaignThemeRgb,
        $title,
        $description,
        $location,
        $tagId,
        $tagName
    ) {
        $this->id = $id;
        $this->campaignThemeName = $campaignThemeName;
        $this->campaignThemeRgb = $campaignThemeRgb;
        $this->title = $title;
        $this->description = $description;
        $this->location = $location;
        $this->tagId = $tagId;
        $this->tagName = $tagName;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCampaignTheme(): array
    {
        return [
            'name' => $this->campaignThemeName,
            'rgb'  => $this->campaignThemeRgb,
        ];
    }

    public function getTags(): array
    {
        $tagIds   = explode(',', $this->tagId);
        $tagNames = explode(',', $this->tagName);

        $tags = [];

        foreach ($tagIds as $ti => $tagId) {
            $tags[$ti] = [
                'id'   => $tagId,
                'name' => $tagNames[$ti],
            ];
        }

        return $tags;
    }

    public function getTitle()
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
