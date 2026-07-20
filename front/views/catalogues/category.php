<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$category = $category ?? ['id_categorie' => 0, 'nom' => $slug ?? ''];
$prestations = $prestations ?? [];
$categoryMediaMap = $categoryMediaMap ?? [];
$prestationMediaMap = $prestationMediaMap ?? [];
$isClientLoggedIn = !empty($_SESSION['client']);

$txt = [
    'fr' => [
        'title_prefix' => 'Rubrique',
        'summary' => 'Les prestations disponibles pour cette rubrique sont affichees ci-dessous.',
        'provider' => 'Prestataire',
        'back' => 'Retour catalogues',
        'cart' => 'Voir mon panier',
        'login' => 'Connexion',
        'empty' => 'Aucune prestation trouvee.',
        'media' => 'Image prestation a definir',
        'photo' => 'Voir la photo',
        'video' => 'Ouvrir la video',
        'service_btn' => 'Voir la prestation',
    ],
    'en' => [
        'title_prefix' => 'Category',
        'summary' => 'Available services for this category are listed below.',
        'provider' => 'Provider',
        'back' => 'Back to catalogues',
        'cart' => 'View my cart',
        'login' => 'Login',
        'empty' => 'No service found.',
        'media' => 'Service image to be set',
        'photo' => 'View photo',
        'video' => 'Open video',
        'service_btn' => 'View service',
    ],
];
$t = $txt[$lang];

$categoryKey = 'catalogue-category-' . (int) $category['id_categorie'];
$categoryMedia = $categoryMediaMap[$categoryKey] ?? null;
?>

<section class="apropos">
    <div class="admin-media-shell">
        <article class="panier-shell">
            <h1 class="titre-texte"><?= e($t['title_prefix']) ?> <?= e($category['nom']) ?></h1>

            <?php if (!empty($categoryMedia['media_url'])): ?>
                <div class="event-media-slot panier-media-slot catalogue-header-polaroid">
                    <?php if (($categoryMedia['media_type'] ?? 'image') === 'video'): ?>
                        <video class="event-video" controls preload="metadata">
                            <source src="<?= e(img_url((string)$categoryMedia['media_url'])) ?>">
                        </video>
                    <?php else: ?>
                        <img src="<?= e(img_url((string)$categoryMedia['media_url'])) ?>" alt="<?= e($categoryMedia['title'] ?? $category['nom']) ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="panier-summary-card">
                <p><?= e($t['summary']) ?></p>
            </div>

            <div class="admin-form-actions">
                <a class="btn" href="<?= route('catalogues') . '?lang=' . $lang ?>"><?= e($t['back']) ?></a>
                <?php if ($isClientLoggedIn): ?>
                    <a class="btn" href="<?= route('panier') . '?lang=' . $lang ?>"><?= e($t['cart']) ?></a>
                <?php else: ?>
                    <a class="btn" href="<?= route('login') . '?lang=' . $lang ?>"><?= e($t['login']) ?></a>
                <?php endif; ?>
            </div>
        </article>

        <?php if (empty($prestations)): ?>
            <p><?= e($t['empty']) ?></p>
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
                                        <source src="<?= e(img_url((string)$media['media_url'])) ?>">
                                    </video>
                                <?php else: ?>
                                    <a href="<?= e(img_url((string)$media['media_url'])) ?>" target="_blank" rel="noopener noreferrer" aria-label="Voir la photo de <?= e($prestation['nom']) ?>">
                                        <img src="<?= e(img_url((string)$media['media_url'])) ?>" alt="<?= e($media['title'] ?? $prestation['nom']) ?>">
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <span><?= e($t['media']) ?></span>
                            <?php endif; ?>
                        </div>
                        <h3><?= e($prestation['nom']) ?></h3>
                        <?php if (!empty($prestation['prestataire_name'])): ?>
                            <p class="card-text"><strong><?= e($t['provider']) ?>:</strong> <?= e($prestation['prestataire_name']) ?></p>
                        <?php endif; ?>
                        <p class="card-text"><?= e(number_format((float) $prestation['prix_unitaire'], 2, ',', ' ')) ?> EUR</p>
                        <?php if (!empty($media['media_url']) && (($media['media_type'] ?? 'image') !== 'video')): ?>
                            <a class="btn" href="<?= e(img_url((string)$media['media_url'])) ?>" target="_blank" rel="noopener noreferrer"><?= e($t['photo']) ?></a>
                        <?php elseif (!empty($media['media_url'])): ?>
                            <a class="btn" href="<?= e(img_url((string)$media['media_url'])) ?>" target="_blank" rel="noopener noreferrer"><?= e($t['video']) ?></a>
                        <?php endif; ?>
                        <a class="btn" href="<?= route('prestations_show', ['id' => $prestation['id_prestation']]) . '?lang=' . $lang ?>"><?= e($t['service_btn']) ?></a>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>