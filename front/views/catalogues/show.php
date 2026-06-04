<?php require_once __DIR__ . '/../../helpers/view.php'; ?>
<?php
if (!isset($prestation)) {
    $prestation = [
        'nom' => '',
        'category_name' => '',
        'prix_unitaire' => '',
        'description' => '',
        'id_prestation' => '',
        'category_slug' => ''
    ];
}

$prestationMediaMap = $prestationMediaMap ?? [];
$mediaKey = 'catalogue-prestation-' . (int) ($prestation['id_prestation'] ?? 0);
$media = $prestationMediaMap[$mediaKey] ?? null;
?>

<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <article class="polaroid event-polaroid catalogue-show-polaroid">
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
                    <span>Image prestation a definir dans Admin medias evenement</span>
                <?php endif; ?>
            </div>
            <h1><?= e($prestation['nom']) ?></h1>
            <p class="card-text">Categorie : <?= e($prestation['category_name']) ?></p>
            <p class="card-text">Prix : <?= e(number_format((float) $prestation['prix_unitaire'], 2, ',', ' ')) ?> EUR</p>
            <p class="card-text"><?= e($prestation['description']) ?></p>

            <?php if (!empty($_SESSION['client'])): ?>
                <form action="<?= route('panier_add', ['id' => $prestation['id_prestation']]) ?>" method="post">
                    <button class="admin-btn" type="submit">Ajouter au panier</button>
                </form>
            <?php else: ?>
                <p class="card-text">
                    Connectez-vous pour demander un devis.
                    <a href="<?= route('login') ?>">Connexion</a>
                </p>
            <?php endif; ?>

            <a class="btn" href="<?= route('catalogues_category', ['slug' => $prestation['category_slug']]) ?>">
                Retour categorie
            </a>
        </article>
    </div>
</section>