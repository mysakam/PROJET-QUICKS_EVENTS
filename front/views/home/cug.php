<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));

$t = [
    'fr' => [
        'title' => "Conditions d'utilisation (CUG)",
        'intro' => "L'utilisation de QUICK'EVENTS implique l'acceptation des conditions ci-dessous.",
        'sections' => [
            [
                'title' => '1. Objet',
                'content' => "La plateforme QUICK'EVENTS permet de consulter un catalogue, créer des demandes de devis et suivre les échanges avec les prestataires.",
            ],
            [
                'title' => '2. Compte utilisateur',
                'content' => "Vous êtes responsable des informations transmises lors de l'inscription et de la confidentialité de vos accès.",
            ],
            [
                'title' => '3. Données et devis',
                'content' => "Les informations d'événement saisies servent à produire des devis. Les montants et validations restent soumis à confirmation par l'administration.",
            ],
            [
                'title' => '4. Disponibilité du service',
                'content' => "Nous faisons notre possible pour assurer la disponibilité de la plateforme, sans garantir l'absence d'interruption technique.",
            ],
            [
                'title' => '5. Contact',
                'content' => "Pour toute question: 0603595028 - akamsamy69@gmail.com",
            ],
        ],
    ],
    'en' => [
        'title' => 'Terms of use (CUG)',
        'intro' => "Using QUICK'EVENTS means accepting the terms below.",
        'sections' => [
            [
                'title' => '1. Purpose',
                'content' => 'The QUICK\'EVENTS platform allows users to browse the catalogue, create quote requests, and track provider exchanges.',
            ],
            [
                'title' => '2. User account',
                'content' => 'You are responsible for the information provided during registration and for keeping your credentials secure.',
            ],
            [
                'title' => '3. Data and quotes',
                'content' => 'Event details are used to generate quotes. Prices and validations remain subject to final admin confirmation.',
            ],
            [
                'title' => '4. Service availability',
                'content' => 'We strive to keep the platform available, but uninterrupted access cannot be guaranteed at all times.',
            ],
            [
                'title' => '5. Contact',
                'content' => 'For any request: 0603595028 - akamsamy69@gmail.com',
            ],
        ],
    ],
];

$txt = $t[$lang];
?>

<section class="cug-page">
    <div class="cug-shell">
        <h1 class="cug-title"><?= e($txt['title']) ?></h1>
        <p class="cug-intro"><?= e($txt['intro']) ?></p>

        <div class="cug-list">
            <?php foreach ($txt['sections'] as $item): ?>
                <article class="cug-item">
                    <h2><?= e($item['title']) ?></h2>
                    <p><?= e($item['content']) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>