<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
if (!isset($prestation)) {
    $prestation = [
        'nom' => '',
        'category_name' => '',
        'prix_unitaire' => '',
        'description' => '',
        'id_prestation' => '',
        'category_slug' => ''
    ]; // Valeurs par défaut pour éviter les erreurs d'affichage si $prestation n'est pas défini.
}

$prestationMediaMap = $prestationMediaMap ?? [];
$providerMedias = $providerMedias ?? [];
$mediaKey = 'catalogue-prestation-' . (int) ($prestation['id_prestation'] ?? 0);
$media = $prestationMediaMap[$mediaKey] ?? null;

$txt = [
    'fr' => [
        'service_prefix' => 'Prestation',
        'category' => 'Catégorie',
        'price' => 'Prix',
        'add_cart' => 'Ajouter au panier',
        'login' => 'Connexion pour ajouter',
        'back' => 'Retour catégorie',
        'message' => 'Connectez-vous pour demander un devis.',
    ],
    'en' => [
        'service_prefix' => 'Service',
        'category' => 'Category',
        'price' => 'Price',
        'add_cart' => 'Add to cart',
        'login' => 'Login to add',
        'back' => 'Back to category',
        'message' => 'Log in to request a quote.',
    ],
];
$t = $txt[$lang];
?>

<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <article class="catalogue-detail-card panier-shell">
            <h1 class="titre-texte"><?= e($t['service_prefix']) ?>: <?= e($prestation['nom']) ?></h1>

            <?php if (!empty($media['media_url'])): ?>
                <div class="event-media-slot panier-media-slot">
                    <?php if (($media['media_type'] ?? 'image') === 'video'): ?>
                        <video class="event-video" controls preload="metadata">
                            <source src="<?= e(img_url((string)$media['media_url'])) ?>">
                        </video>
                    <?php else: ?>
                        <img src="<?= e(img_url((string)$media['media_url'])) ?>" alt="<?= e($media['title'] ?? $prestation['nom']) ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="panier-summary-card">
                <p class="card-text"><strong><?= e($t['category']) ?>:</strong> <?= e($prestation['category_name']) ?></p>
                <p class="card-text"><strong><?= e($t['price']) ?>:</strong>
                    <?= e(number_format((float) $prestation['prix_unitaire'], 2, ',', ' ')) ?> EUR</p>
                <p class="card-text"><?= e($prestation['description']) ?></p>
            </div>

            <?php if ($providerMedias !== []): ?>
                <div class="panier-summary-card" style="margin-top: 12px;">
                    <h3 style="margin-top:0;">Realisations du prestataire</h3>
                    <div class="theme-grid catalogue-grid" style="margin-top:10px;">
                        <?php foreach ($providerMedias as $providerMedia): ?>
                            <article class="polaroid event-polaroid">
                                <div class="event-media-slot">
                                    <?php if (($providerMedia['media_type'] ?? 'image') === 'video'): ?>
                                        <video class="event-video" controls preload="metadata">
                                            <source src="<?= e(img_url((string) ($providerMedia['media_url'] ?? ''))) ?>">
                                        </video>
                                    <?php else: ?>
                                        <img src="<?= e(img_url((string) ($providerMedia['media_url'] ?? ''))) ?>" alt="<?= e($providerMedia['title'] ?? 'Media prestataire') ?>">
                                    <?php endif; ?>
                                </div>
                                <h3><?= e($providerMedia['title'] ?? 'Media') ?></h3>
                                <?php if (!empty($providerMedia['description'])): ?>
                                    <p class="card-text"><?= e($providerMedia['description']) ?></p>
                                <?php endif; ?>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="admin-form-actions">
                <?php if (!empty($_SESSION['client'])): ?>
                    <form action="<?= route('panier_add', ['id' => $prestation['id_prestation']]) . '?lang=' . $lang ?>" method="post" data-fetch-form>
                        <button class="admin-btn" type="submit"><?= e($t['add_cart']) ?></button>
                    </form>
                <?php else: ?>
                    <a class="btn" href="<?= route('login') . '?lang=' . $lang ?>"><?= e($t['login']) ?></a>
                <?php endif; ?>

                <a class="btn" href="<?= route('catalogues_category', ['slug' => $prestation['category_slug']]) . '?lang=' . $lang ?>">
                    <?= e($t['back']) ?>
                </a>
            </div>
            <?php if (empty($_SESSION['client'])): ?>
                <p class="card-text"><?= e($t['message']) ?></p>
            <?php endif; ?>
        </article>
    </div>
</section>