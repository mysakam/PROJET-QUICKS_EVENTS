<?php
$lang = ($lang ?? 'fr') === 'en' ? 'en' : 'fr';
$langQuery = '?lang=' . $lang;
$homeUrl = route('home') . $langQuery;
$catalogueUrl = route('catalogues') . $langQuery;
?>
<section class="banniere theme-banner">
    <div class="contenu">
        <h1><?= e($page['title_' . $lang] ?? '') ?></h1>
        <p><?= e($page['subtitle_' . $lang] ?? '') ?></p>

        <div class="theme-hero-actions">
            <a href="<?= $catalogueUrl ?>"
                class="btn"><?= $lang === 'fr' ? 'Voir le catalogue' : 'Browse catalogue' ?></a>
            <a href="<?= $homeUrl ?>" class="btn"><?= $lang === 'fr' ? 'Retour à l’accueil' : 'Back to home' ?></a>
        </div>
    </div>
</section>

<section class="apropos">
    <div class="theme-grid">
        <?php foreach ($polaroids as $polaroid): ?>
        <article class="polaroid event-polaroid">
            <h3><?= e($polaroid['title']) ?></h3>

            <div class="image event-media-slot">
                <?php if (!empty($polaroid['mediaSrc'])): ?>
                <img src="<?= e($polaroid['mediaSrc']) ?>" alt="<?= e($polaroid['title']) ?>">
                <?php else: ?>
                <span><?= e($polaroid['mediaLabel']) ?></span>
                <?php endif; ?>
            </div>

            <div class="card-text event-description"><?= e($polaroid['description']) ?></div>
            <a href="<?= $catalogueUrl ?>"
                class="btn"><?= $lang === 'fr' ? 'Voir le catalogue' : 'Browse catalogue' ?></a>
        </article>
        <?php endforeach; ?>
    </div>

    <div class="theme-actions">
        <a href="<?= $homeUrl ?>"
            class="btn"><?= e($page['back_label_' . $lang] ?? ($lang === 'fr' ? "Retour à l'accueil" : 'Back to home')) ?></a>
    </div>
</section>