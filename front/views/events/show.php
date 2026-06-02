<?php
$langQuery = '?lang=' . ($lang ?? 'fr');
$homeUrl = route('home') . $langQuery;
$catalogueUrl = route('catalogues') . $langQuery;
$toggleLang = ($lang ?? 'fr') === 'fr' ? 'en' : 'fr';
?>
<!DOCTYPE html>
<html lang="<?= e($lang ?? 'fr') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <title><?= e($page['title_' . ($lang ?? 'fr')] ?? 'QUICK\'EVENTS') ?></title>
</head>

<body>
    <header>
        <a href="<?= $homeUrl ?>" class="logo"><span> QUICK'EVENTS </span></a>
        <ul class="navbar">
            <li><a href="<?= $homeUrl ?>#apropos" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'A PROPOS' : 'ABOUT' ?></a></li>
            <li><a href="<?= $catalogueUrl ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'CATALOGUES' : 'CATALOGUES' ?></a></li>
            <li><a href="<?= route('login') . $langQuery ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'CONNEXION' : 'LOGIN' ?></a></li>
            <li><a href="<?= route('register') . $langQuery ?>" class="btn"><?= ($lang ?? 'fr') === 'fr' ? 'INSCRIPTION' : 'REGISTER' ?></a></li>
            <a href="<?= $homeUrl ?>?lang=<?= $toggleLang ?>" class="btn-transcription"><?= ($lang ?? 'fr') === 'fr' ? 'FR/EN' : 'EN/FR' ?></a>
        </ul>
    </header>

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
</body>

</html>