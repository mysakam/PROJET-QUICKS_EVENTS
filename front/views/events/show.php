<?php
$langQuery = '?lang=' . ($lang ?? 'fr');
$homeUrl = route('home') . $langQuery;
$catalogueUrl = route('catalogues') . $langQuery;
?>

<section class="banniere theme-banner" id="banniere">
    <div class="contenu">
        <h1><?= e($page['title_' . ($lang ?? 'fr')] ?? '') ?></h1>
        <p><?= e($page['subtitle_' . ($lang ?? 'fr')] ?? '') ?></p>
        <div class="theme-hero-actions">
            <a href="<?= $catalogueUrl ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'Voir le catalogue' : 'Browse catalogue' ?></a>
            <a href="<?= $homeUrl ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'Retour à l’accueil' : 'Back to home' ?></a>
        </div>
    </div>
</section>

<section class="apropos" id="apropos">
    <div class="rowApropos theme-grid">
        <?php foreach ($polaroids as $polaroid): ?>
            <div class="polaroid event-polaroid">
                <h3><?= e($polaroid['title']) ?></h3>
                <div class="image event-media-slot">
                    <?php if (!empty($polaroid['mediaSrc'])): ?>
                        <img src="<?= e($polaroid['mediaSrc']) ?>" alt="<?= e($polaroid['title']) ?>">
                    <?php else: ?>
                        <span><?= e($polaroid['mediaLabel']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="card-text event-description"><?= e($polaroid['description']) ?></div>
                <a href="<?= $catalogueUrl ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'Voir le catalogue' : 'Browse catalogue' ?></a>
                <?php if (!empty($_SESSION['client']) && !empty($_SESSION['client']['is_admin'])): ?>
                    <a href="#" class="btn theme-video-btn"><?= ($lang ?? 'fr') === 'fr' ? 'Ajouter une vidéo plus tard' : 'Add a video later' ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="action theme-actions">
        <a href="<?= $homeUrl ?>" class="btn theme-back-btn"><?= e($page['back_label_' . ($lang ?? 'fr')] ?? (($lang ?? 'fr') === 'fr' ? 'Retour à l\'accueil' : 'Back to home')) ?></a>
    </div>
</section>