<?php
$lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
$langQuery = '?lang=' . $lang;
$catalogueUrl = route('catalogues') . $langQuery;
// Les tableaux (who_lines, how_lines, cards…) sont lus depuis le dictionnaire centralisé
$_allT       = require dirname(__DIR__, 2) . '/lang/translations.php';
$_home       = $_allT[$lang]['home'];
$eventRoutes = ['event_mariage', 'event_anniversaire', 'event_soiree_theme', 'event_repas_seminaire'];
?>

<section class="banniere" id="banniere">
    <div class="contenu">
        <h1><?= e(t('home.hero_title', $lang)) ?></h1>
        <p><?= e(t('home.hero_text', $lang)) ?></p>
    </div>
</section>
<section class="apropos" id="apropos">
    <div class="rowApropos">
        <div class="col50">
            <h2 class="titre-texte">
                <?= e(t('home.who_title', $lang)) ?>
            </h2>
            <ul>
                <?php foreach ($_home['who_lines'] as $line): ?>
                    <li><?= e($line) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="col50">
            <div class="img">
                <img src="<?= img_url('/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif') ?>"
                    alt="event image">
            </div>
        </div>
        <div class="col50">
            <h2 class="titre-texte">
                <?= e(t('home.how_title', $lang)) ?>
            </h2>
            <ol>
                <?php foreach ($_home['how_lines'] as $line): ?>
                    <li><?= e($line) ?></li>
                <?php endforeach; ?>
            </ol>
        </div>
    </div>

    <?php if (!empty($_SESSION['client'])): ?>
        <div class="about-cta">
            <p><?= e(t('home.about_cta', $lang)) ?></p>
            <a class="admin-btn about-cta-btn" href="<?= route('mon_evenement') . $langQuery ?>"><?= e(t('home.about_cta_btn', $lang)) ?></a>
        </div>
    <?php endif; ?>
</section>
<section class="evenements" id="evenements">
    <h2 class="titre-texte"><?= e(t('home.events_title', $lang)) ?></h2>
    <div class="row">
        <?php
        $_imgHome = [
            img_url('/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp'),
            img_url('/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg'),
            img_url('/assets/css/images/image-45-768x768.jpeg'),
            img_url('/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg'),
        ];
        foreach ($_home['cards'] as $_i => $_card): ?>
            <div class="polaroid">
                <h3><?= e($_card['title']) ?></h3>
                <div class="image">
                    <img src="<?= $_imgHome[$_i] ?>" alt="<?= e($_card['title']) ?>">
                </div>
                <div class="card-text"><?= e($_card['text']) ?></div>
                <a href="<?= route($eventRoutes[$_i]) . $langQuery ?>" class="btn"><?= e($_home['event_links'][$_i]) ?></a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="action">
        <h3 class="action-title"><?= e(t('home.bottom_title', $lang)) ?></h3>
        <div>
            <a href="<?= $catalogueUrl ?>" class="btn"><?= e(t('home.bottom_cta', $lang)) ?></a>
        </div>
    </div>
</section>