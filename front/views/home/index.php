<?php
$lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
$toggleLang = $lang === 'fr' ? 'en' : 'fr';

$t = [
    'fr' => [
        'about' => 'A PROPOS',
        'events' => 'EVENEMENTS',
        'login' => 'CONNEXION',
        'register' => 'INSCRIPTION',
        'lang_toggle' => 'FR/EN',
        'hero_title' => 'ORGANISEZ VOS EVENEMENTS EN UN CLIC...',
        'hero_text' => 'Choisissez des prestataires evenementiels et des sites evenementiels qui vous conviennent.',
        'who_title' => 'Qui sommes nous ?',
        'who_lines' => [
            "Un conglomerat d'experts en organisation d'evenements.",
            "Nous couvrons tous les aspects de la planification d'evenements.",
            'Nous garantissons la qualite et la satisfaction de nos clients.',
            'Nous innovons constamment pour offrir des experiences uniques.',
        ],
        'how_title' => 'Comment ca marche ?',
        'how_lines' => [
            'Choisissez un prestataire ou un site evenementiel.',
            'Personnalisez votre evenement selon vos besoins.',
            'Recevez votre devis personnalise.',
            "Confirmez et finalisez votre reservation (minimum 24 heures avant l'evenement).",
        ],
        'events_title' => 'NOTRE RUBRIQUE EVENEMENTS',
        'discover_label' => 'Voir le catalogue',
        'bottom_title' => 'Pret a les epater...',
        'bottom_cta' => 'Decouvrir les catalogues',
        'cards' => [
            ['title' => 'Mariage', 'text' => 'Organisez votre mariage de reve avec nos prestataires et sites evenementiels de qualite.'],
            ['title' => 'Anniversaire', 'text' => 'Celebrez votre anniversaire de maniere inoubliable avec nos prestataires evenementiels.'],
            ['title' => 'Soiree a theme', 'text' => 'Organisez une soiree memorable avec nos prestataires specialises.'],
            ['title' => 'Repas seminaire', 'text' => 'Organisez un evenement professionnel reussi avec nos partenaires.'],
        ],
    ],
    'en' => [
        'about' => 'ABOUT',
        'events' => 'EVENTS',
        'login' => 'LOGIN',
        'register' => 'REGISTER',
        'lang_toggle' => 'EN/FR',
        'hero_title' => 'ORGANIZE YOUR EVENTS IN ONE CLICK...',
        'hero_text' => 'Choose event providers and venues that match your needs.',
        'who_title' => 'Who are we?',
        'who_lines' => [
            'A collective of experts in event planning.',
            'We cover every aspect of event preparation.',
            'We guarantee quality and customer satisfaction.',
            'We keep innovating to create unique experiences.',
        ],
        'how_title' => 'How does it work?',
        'how_lines' => [
            'Choose a provider or event venue.',
            'Customize your event according to your needs.',
            'Receive your personalized quote.',
            'Confirm and finalize your booking (at least 24 hours before the event).',
        ],
        'events_title' => 'OUR EVENT CATEGORIES',
        'discover_label' => 'Browse catalogue',
        'bottom_title' => 'Ready to impress...',
        'bottom_cta' => 'Discover catalogues',
        'cards' => [
            ['title' => 'Wedding', 'text' => 'Plan your dream wedding with our curated providers and venues.'],
            ['title' => 'Birthday', 'text' => 'Celebrate your birthday in style with event services tailored to you.'],
            ['title' => 'Theme party', 'text' => 'Create a memorable themed party with specialized providers.'],
            ['title' => 'Seminar meal', 'text' => 'Host a successful business event with the right partners.'],
        ],
    ],
];

$txt = $t[$lang];
$langQuery = '?lang=' . $lang;
$catalogueUrl = route('catalogues') . $langQuery;
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>QUICK'EVENTS</title>
</head>

<body>
    <header>
        <a href="<?= route('home') . $langQuery ?>" class="logo"><span> QUICK'EVENTS </span></a>
        <ul class="navbar">
            <li><a href="#apropos" class="btn"><?= $txt['about'] ?></a></li>
            <li><a href="#evenements" class="btn"><?= $txt['events'] ?></a></li>
            <li><a href="<?= route('login') . $langQuery ?>" class="btn"><?= $txt['login'] ?></a></li>
            <li><a href="<?= route('register') . $langQuery ?>" class="btn"><?= $txt['register'] ?></a></li>
            <a href="<?= route('home') . '?lang=' . $toggleLang ?>" class="btn-transcription"><?= $txt['lang_toggle'] ?></a>
        </ul>
    </header>
    <section class="banniere" id="banniere">
        <div class="contenu">
            <h1><?= $txt['hero_title'] ?></h1>
            <p><?= $txt['hero_text'] ?></p>

        </div>
    </section>
    <section class="apropos" id="apropos">
        <div class="rowApropos">
            <div class="col50">
                <h2 class="titre-texte">
                    <span><?= $lang === 'fr' ? 'Q' : 'W' ?></span><?= $lang === 'fr' ? 'ui sommes nous ?' : 'ho are we?' ?>
                </h2>
                <ul>
                    <?php foreach ($txt['who_lines'] as $line): ?>
                        <li><?= $line ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col50">
                <div class="img">
                    <img src="/assets/css/images/pouring-champagne-into-glass-wedding-celebration_921860-20817.avif"
                        alt="event image">
                </div>
            </div>
            <div class="col50">
                <h2 class="titre-texte">
                    <span><?= $lang === 'fr' ? 'C' : 'H' ?></span><?= $lang === 'fr' ? 'omment ca marche ?' : 'ow does it work?' ?>
                </h2>
                <ol>
                    <?php foreach ($txt['how_lines'] as $line): ?>
                        <li><?= $line ?></li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </section>
    <section class="evenements" id="evenements">
        <h2 class="titre-texte"><span><?= $txt['events_title'] ?></span></h2>
        <div class="row">
            <div class="card">
                <h3><?= $txt['cards'][0]['title'] ?></h3>
                <div>
                    <img src="/assets/css/images/grand-wedding-decoration-country-manor-floral-decor-event-celebration-flowers-aisle-tablescape-garden-english-350874308.webp"
                        alt="<?= $txt['cards'][0]['title'] ?>">
                </div>
                <div class="card-text"><?= $txt['cards'][0]['text'] ?></div>
                <a href="<?= $catalogueUrl ?>" class="btn"><?= $txt['discover_label'] ?></a>
            </div>
            <div class="card">
                <h3><?= $txt['cards'][1]['title'] ?></h3>
                <div>
                    <img src="/assets/css/images/bf2c558e260f6a735bc2346e5e5dff5a.jpg" alt="<?= $txt['cards'][1]['title'] ?>">
                </div>
                <div class="card-text"><?= $txt['cards'][1]['text'] ?></div>
                <a href="<?= $catalogueUrl ?>" class="btn"><?= $txt['discover_label'] ?></a>
            </div>
            <div class="card">
                <h3><?= $txt['cards'][2]['title'] ?></h3>
                <div>
                    <img src="/assets/css/images/image-45-768x768.jpeg" alt="<?= $txt['cards'][2]['title'] ?>">
                </div>
                <div class="card-text"><?= $txt['cards'][2]['text'] ?></div>
                <a href="<?= $catalogueUrl ?>" class="btn"><?= $txt['discover_label'] ?></a>
            </div>
            <div class="card">
                <h3><?= $txt['cards'][3]['title'] ?></h3>
                <div>
                    <img src="/assets/css/images/Soiree-vip-gala-soiree-nova-saint-malo-35-scaled.jpg" alt="<?= $txt['cards'][3]['title'] ?>">
                </div>
                <div class="card-text"><?= $txt['cards'][3]['text'] ?></div>
                <a href="<?= $catalogueUrl ?>" class="btn"><?= $txt['discover_label'] ?></a>
            </div>
        </div>
        <div class="action">
            <h3><?= $txt['bottom_title'] ?></h3>
            <div>
                <a href="<?= $catalogueUrl ?>" class="btn"><?= $txt['bottom_cta'] ?></a>
            </div>
        </div>
    </section>
</body>

</html>