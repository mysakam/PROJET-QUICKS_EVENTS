<?php
$langQuery = '?lang=' . lang();
$catalogueUrl = route('catalogues') . $langQuery;

$cards = [
    ['title' => t('home.card_1'), 'route' => 'event_mariage', 'img' => '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp'],
    ['title' => t('home.card_2'), 'route' => 'event_anniversaire', 'img' => '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg'],
    ['title' => t('home.card_3'), 'route' => 'event_soiree_theme', 'img' => '/assets/css/images/image-45-768x768.jpeg'],
    ['title' => t('home.card_4'), 'route' => 'event_repas_seminaire', 'img' => '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg'],
];
?>

<section class="home-hero">
    <div class="home-hero__overlay">
        <div class="home-hero__content">
            <h1><?= e(t('home.hero_title')) ?></h1>
            <p><?= e(t('home.hero_text')) ?></p>
        </div>
    </div>
</section>

<section class="home-steps">
    <div class="home-steps__overlay">
        <div class="home-steps__content">
            <h2><?= e(t('home.steps_title')) ?></h2>

            <ul class="home-steps__list">
                <li><?= e(t('home.steps_1')) ?></li>
                <li><?= e(t('home.steps_2')) ?></li>
                <li><?= e(t('home.steps_3')) ?></li>
                <li><?= e(t('home.steps_4')) ?></li>
                <li><?= e(t('home.steps_5')) ?></li>
                <li><?= e(t('home.steps_6')) ?></li>
            </ul>
        </div>
    </div>
</section>

<section class="home-events" id="evenements">
    <h2 class="home-section-title"><?= e(t('home.events_title')) ?></h2>

    <div class="home-cards">
        <?php foreach ($cards as $card): ?>
        <article class="home-card">
            <a href="<?= route($card['route']) . $langQuery ?>" class="home-card__link">
                <img src="<?= e($card['img']) ?>" alt="<?= e($card['title']) ?>">
                <span><?= e($card['title']) ?></span>
            </a>
        </article>
        <?php endforeach; ?>
    </div>

    <div class="home-cta">
        <h3><?= e(t('home.cta_title')) ?></h3>
        <a href="<?= $catalogueUrl ?>" class="btn"><?= e(t('home.cta_btn')) ?></a>
    </div>
</section>