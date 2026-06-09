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
        <article class="catalogue-detail-card panier-shell">
            <h1 class="titre-texte"><span>P</span>restation: <?= e($prestation['nom']) ?></h1>

            <?php if (!empty($media['media_url'])): ?>
                <div class="event-media-slot panier-media-slot">
                    <?php if (($media['media_type'] ?? 'image') === 'video'): ?>
                        <video class="event-video" controls preload="metadata">
                            <source src="<?= e($media['media_url']) ?>">
                        </video>
                    <?php else: ?>
                        <img src="<?= e($media['media_url']) ?>" alt="<?= e($media['title'] ?? $prestation['nom']) ?>">
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="panier-summary-card">
                <p class="card-text"><strong>Categorie:</strong> <?= e($prestation['category_name']) ?></p>
                <p class="card-text"><strong>Prix:</strong> <?= e(number_format((float) $prestation['prix_unitaire'], 2, ',', ' ')) ?> EUR</p>
                <p class="card-text"><?= e($prestation['description']) ?></p>
            </div>

            <div class="admin-form-actions">
                <?php if (!empty($_SESSION['client'])): ?>
                    <form action="<?= route('panier_add', ['id' => $prestation['id_prestation']]) ?>" method="post">
                        <button class="admin-btn" type="submit">Ajouter au panier</button>
                    </form>
                <?php else: ?>
                    <a class="btn" href="<?= route('login') ?>">Connexion pour ajouter</a>
                <?php endif; ?>

                <a class="btn" href="<?= route('catalogues_category', ['slug' => $prestation['category_slug']]) ?>">
                    Retour categorie
                </a>
            </div>
            <?php if (empty($_SESSION['client'])): ?>
                <p class="card-text">Connectez-vous pour demander un devis.</p>
            <?php endif; ?>
        </article>
    </div>
</section>