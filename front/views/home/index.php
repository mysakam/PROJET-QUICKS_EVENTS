<?php
$langQuery = '?lang=' . lang();
$catalogueUrl = route('catalogues') . $langQuery;

$cards = [
    [
        'title' => t('home.card_1'),
        'text' => t('home.card_1_text'),
        'route' => 'event_mariage',
        'img' => '/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp',
    ],
    [
        'title' => t('home.card_2'),
        'text' => t('home.card_2_text'),
        'route' => 'event_anniversaire',
        'img' => '/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg',
    ],
    [
        'title' => t('home.card_3'),
        'text' => t('home.card_3_text'),
        'route' => 'event_soiree_theme',
        'img' => '/assets/css/images/image-45-768x768.jpeg',
    ],
    [
        'title' => t('home.card_4'),
        'text' => t('home.card_4_text'),
        'route' => 'event_repas_seminaire',
        'img' => '/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg',
    ],
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

<section class="home-about">
    <div class="home-about__col home-about__col--text">
        <h2><?= e(t('home.about_title')) ?></h2>
        <ul class="home-about__list">
            <li><?= e(t('home.about_1')) ?></li>
            <li><?= e(t('home.about_2')) ?></li>
            <li><?= e(t('home.about_3')) ?></li>
            <li><?= e(t('home.about_4')) ?></li>
        </ul>
    </div>

    <div class="home-about__col home-about__col--image">
        <img src="/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif"
            alt="<?= e(t('home.about_image_alt')) ?>">
    </div>

    <div class="home-about__col home-about__col--steps">
        <h2><?= e(t('home.steps_title')) ?></h2>
        <ol class="home-steps-list">
            <li><?= e(t('home.steps_1')) ?></li>
            <li><?= e(t('home.steps_2')) ?></li>
            <li><?= e(t('home.steps_3')) ?></li>
            <li><?= e(t('home.steps_4')) ?></li>
        </ol>
    </div>
</section>

<section class="home-events">
    <div class="home-events__overlay">
        <h2 class="home-section-title"><?= e(t('home.events_title')) ?></h2>

        <div class="home-cards">
            <?php foreach ($cards as $i => $card): ?>
            <article class="home-card home-card--<?= $i + 1 ?>">
                <h3><?= e($card['title']) ?></h3>
                <img src="<?= e($card['img']) ?>" alt="<?= e($card['title']) ?>">
                <p><?= e($card['text']) ?></p>
                <a href="<?= route($card['route']) . $langQuery ?>" class="home-card__btn">
                    <?= e(t('home.card_link')) ?> <?= e($card['title']) ?>
                </a>
            </article>
            <?php endforeach; ?>
        </div>

        <div class="home-cta">
            <h3><?= e(t('home.cta_title')) ?></h3>
            <a href="<?= $catalogueUrl ?>" class="home-cta__btn"><?= e(t('home.cta_btn')) ?></a>
        </div>
    </div>
</section>