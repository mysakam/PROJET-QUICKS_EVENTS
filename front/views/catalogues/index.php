<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
$categories = $categories ?? [];
$categoryMediaMap = $categoryMediaMap ?? [];
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h1 class="titre-texte"><span>C</span>atalogues</h1>
        <p>Choisissez une rubrique pour voir les prestations disponibles.</p>

        <?php if (empty($categories)): ?>
            <p>Aucune categorie disponible.</p>
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
                                <span>Image a definir dans Admin medias evenement</span>
                            <?php endif; ?>
                        </div>
                        <h3><?= e($category['nom']) ?></h3>
                        <a class="btn" href="<?= route('catalogues_category', ['slug' => $category['slug']]) ?>">Voir les prestations</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>