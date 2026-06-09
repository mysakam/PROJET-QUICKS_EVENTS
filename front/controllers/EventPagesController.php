<?php
private function renderPage(string $slug): void { if (!isset($this->pages[$slug])) {
    http_response_code(404);
    echo 'Page introuvable';
    return;
    }

    $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
    $page = $this->pages[$slug];

    $polaroids = array_map(static function (array $item) use ($lang): array {
    return [
    'title' => $item['title_' . $lang],
    'mediaLabel' => $item['media_' . $lang],
    'mediaSrc' => $item['media_src'] ?? null,
    'description' => $item['description_' . $lang],
    ];
    }, $page['polaroids']);

    $this->render('events/show', [
    'lang' => $lang,
    'page' => $page,
    'polaroids' => $polaroids,
    'slug' => $slug,
    'pageTitle' => $page['title_' . $lang],
    ], 'main');
    }