<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
$category = $category ?? ['id_categorie' => 0, 'nom' => $slug ?? ''];
$prestations = $prestations ?? [];
$categoryMediaMap = $categoryMediaMap ?? [];
$prestationMediaMap = $prestationMediaMap ?? [];
$isClientLoggedIn = !empty($_SESSION['client']);

$categoryKey = 'catalogue-category-' . (int) $category['id_categorie'];
$categoryMedia = $categoryMediaMap[$categoryKey] ?? null;
?>

<section class="apropos">
    <div class="admin-media-shell">
        <article class="panier-shell">
            <h1 class="titre-texte"><span>R</span>ubrique <?= e($category['nom']) ?></h1>

            <?php if (!empty($categoryMedia['media_url'])): ?>
                <div class="event-media-slot panier-media-slot catalogue-header-polaroid">
                    <?php if (($categoryMedia['media_type'] ?? 'image') === 'video'): ?>
                        <video class="event-video" controls preload="metadata">
                            <source src="<?= e($categoryMedia['media_url']) ?>">
                        </video>
                    <?php else: ?>
                        <img src="<?= e($categoryMedia['media_url']) ?>" alt="<?= e($categoryMedia['title'] ?? $category['nom']) ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="panier-summary-card">
                <p>Les prestations disponibles pour cette rubrique sont affichees ci-dessous.</p>
            </div>

            <div class="admin-form-actions">
                <a class="btn" href="<?= route('catalogues') ?>">Retour catalogues</a>
                <?php if ($isClientLoggedIn): ?>
                    <a class="btn" href="<?= route('panier') ?>">Voir mon panier</a>
                <?php else: ?>
                    <a class="btn" href="<?= route('login') ?>">Connexion</a>
                <?php endif; ?>
            </div>
        </article>

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
                                    <a href="<?= e($media['media_url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Voir la photo de <?= e($prestation['nom']) ?>">
                                        <img src="<?= e($media['media_url']) ?>" alt="<?= e($media['title'] ?? $prestation['nom']) ?>">
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span>Image prestation a definir</span>
                            <?php endif; ?>
                        </div>
                        <h3><?= e($prestation['nom']) ?></h3>
                        <p class="card-text"><?= e(number_format((float) $prestation['prix_unitaire'], 2, ',', ' ')) ?> EUR</p>
                        <?php if (!empty($media['media_url']) && (($media['media_type'] ?? 'image') !== 'video')): ?>
                            <a class="btn" href="<?= e($media['media_url']) ?>" target="_blank" rel="noopener noreferrer">Voir la photo</a>
                        <?php elseif (!empty($media['media_url'])): ?>
                            <a class="btn" href="<?= e($media['media_url']) ?>" target="_blank" rel="noopener noreferrer">Ouvrir la video</a>
                        <?php endif; ?>
                        <a class="btn" href="<?= route('prestations_show', ['id' => $prestation['id_prestation']]) ?>">Voir la prestation</a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>