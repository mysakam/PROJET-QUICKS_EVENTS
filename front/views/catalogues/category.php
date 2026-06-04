<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
$category = $category ?? ['id_categorie' => 0, 'nom' => $slug ?? ''];
$prestations = $prestations ?? [];
$categoryMediaMap = $categoryMediaMap ?? [];
$prestationMediaMap = $prestationMediaMap ?? [];

$categoryKey = 'catalogue-category-' . (int) $category['id_categorie'];
$categoryMedia = $categoryMediaMap[$categoryKey] ?? null;
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h1 class="titre-texte"><span>R</span>ubrique <?= e($category['nom']) ?></h1>
        <p>Les prestations disponibles pour cette rubrique sont affichees ci-dessous.</p>

        <?php if (empty($prestations)): ?>
            <p>Aucune prestation trouvee.</p>
        <?php else: ?>
            <div class="theme-grid catalogue-grid">
                <?php foreach ($prestations as $prestation): ?>
                    <?php
                    $key = 'catalogue-prestation-' . (int) $prestation['id_prestation'];
                    $media = $prestationMediaMap[$key] ?? null;
                    ?>
                    <article class="polaroid event-polaroid">
                        <div class="event-media-slot">
                            <?php if (!empty($media['media_url'])): ?>
                                <?php if (($media['media_type'] ?? 'image') === 'video'): ?>
                                    <video class="event-video" controls preload="metadata">
                                        <source src="<?= e($media['media_url']) ?>">
                                    </video>
                                <?php else: ?>
                                    <img src="<?= e($media['media_url']) ?>" alt="<?= e($media['title'] ?? $prestation['nom']) ?>">
                                <?php endif; ?>
                            <?php else: ?>
                                <span>Image prestation a definir</span>
                            <?php endif; ?>
                        </div>
                        <h3><?= e($prestation['nom']) ?></h3>
                        <p class="card-text"><?= e(number_format((float) $prestation['prix_unitaire'], 2, ',', ' ')) ?> EUR</p>
                        <a class="btn" href="<?= route('prestations_show', ['id' => $prestation['id_prestation']]) ?>">Voir la prestation</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="theme-actions">
            <a class="btn theme-back-btn" href="<?= route('catalogues') ?>">Retour catalogues</a>
        </div>
    </div>
</section>