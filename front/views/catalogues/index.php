<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$categories = $categories ?? [];
$categoryMediaMap = $categoryMediaMap ?? [];

$txt = [
    'fr' => [
        'title' => 'Catalogues',
        'intro' => 'Choisissez une rubrique pour voir les prestations disponibles.',
        'empty' => 'Aucune catégorie disponible.',
        'media' => 'Image à définir dans Admin médias événement',
        'btn' => 'Voir les prestations',
    ],
    'en' => [
        'title' => 'Catalogues',
        'intro' => 'Choose a category to see available services.',
        'empty' => 'No category available.',
        'media' => 'Image to be set in event media admin',
        'btn' => 'View services',
    ],
];
$t = $txt[$lang];
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h1 class="titre-texte"><span>C</span><?= $lang === 'fr' ? 'atalogues' : 'atalogues' ?></h1>
        <p><?= e($t['intro']) ?></p>

        <?php if (empty($categories)): ?>
            <p><?= e($t['empty']) ?></p>
        <?php else: ?>
            <div class="theme-grid catalogue-grid">
                <?php foreach ($categories as $category): ?>
                    <?php
                    $key = 'catalogue-category-' . (int) $category['id_categorie'];
                    $media = $categoryMediaMap[$key] ?? null;
                    ?>
                    <article class="polaroid event-polaroid">
                        <div class="event-media-slot">
                            <?php if (!empty($media['media_url'])): ?>
                                <?php if (($media['media_type'] ?? 'image') === 'video'): ?>
                                    <video class="event-video" controls preload="metadata">
                                        <source src="<?= e($media['media_url']) ?>">
                                    </video>
                                <?php else: ?>
                                    <img src="<?= e($media['media_url']) ?>" alt="<?= e($media['title'] ?? $category['nom']) ?>">
                                <?php endif; ?>
                            <?php else: ?>
                                <span><?= e($t['media']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= e($category['nom']) ?></h3>
                        <a class="btn" href="<?= route('catalogues_category', ['slug' => $category['slug']]) . '?lang=' . $lang ?>"><?= e($t['btn']) ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>