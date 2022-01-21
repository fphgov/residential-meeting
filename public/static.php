<?php

declare(strict_types=1);

class OpenGraph {
    private string $title;
    private string $description;

    public string $creator;
    public string $image;

    public function __construct(
        string $title,
        string $description,
        string $creator,
        string $image
    ) {
        $this->title       = $title;
        $this->description = $description;
        $this->creator     = $creator;
        $this->image       = $image;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setDescription(string $content): void
    {
        $description = strip_tags($content);

        $descriptions = explode(" ", $description);
        $descriptions = array_slice($descriptions, 0, min(18, count($descriptions) - 1));

        $description  = implode(" ", $descriptions);
        $description .= ' ...';

        $this->description = $description;
    }

    public function getTags(string $uri)
    {
        return $this->getContent();
    }

    public function getContent()
    {
        return implode("\n", [
            '<title>'. $this->title .'</title>',
            '<link rel="icon" href="/icon_96x96.png" type="image/png">',
            '<meta charset="utf-8">',
            '<meta name="description" content="'. $this->description .'">',
            '<meta property="og:type" content="website">',
            '<meta name="og:title" content="'. $this->title .'">',
            '<meta name="og:description" content="'. $this->description .'">',
            '<meta name="og:image" content="'. $this->image .'">',
            '<meta name="twitter:card" content="summary">',
            '<meta name="twitter:creator" content="'. $this->creator .'">',
            '<meta name="twitter:title" content="'. $this->title .'">',
            '<meta name="twitter:description" content="'. $this->description .'">',
        ]);
    }
}

$og = new OpenGraph(
    "Közösségi költségvetés 2021/2022",
    "Mire költsön 1 milliárd forintot Budapest?",
    "Fővárosi Önkormányzat",
    "https://otlet.budapest.hu/files/og_image.png"
);

$filename = 'seo.json';

clearstatcache();

if (! file_exists($filename) || file_exists($filename) && date('Y-m-d H:i:s', filemtime($filename)) <= (new DateTime('now'))->sub(new DateInterval('PT1H'))) {
    include_once '../bin/create-seo-file.php';

    chdir(__DIR__);
}

if (file_exists($filename)) {
    $ogFile  = file_get_contents($filename);
    $ogMetas = json_decode($ogFile, true);

    $doc = file_get_contents('index.html');

    if (array_key_exists($_SERVER['REQUEST_URI'], $ogMetas)) {
        $og->setTitle($ogMetas[$_SERVER['REQUEST_URI']]['title']);
        $og->setDescription($ogMetas[$_SERVER['REQUEST_URI']]['description']);
    }

    echo preg_replace('/\<meta charset="utf-8"\s?\/?>/', $og->getTags($_SERVER['REQUEST_URI']), $doc);
} else {
    echo file_get_contents('index.html');
}
