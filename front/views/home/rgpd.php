<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));

$t = [
    'fr' => [
        'title' => 'Protection des données (RGPD)',
        'intro' => 'Nous mettons en œuvre des mesures techniques et organisationnelles pour protéger vos données personnelles et limiter leur usage au strict nécessaire.',
        'sections' => [
            [
                'title' => '1. Données collectées',
                'content' => 'Nous collectons uniquement les données utiles à votre compte, à vos devis, à vos factures et à vos échanges avec l\'administration.',
            ],
            [
                'title' => '2. Finalité',
                'content' => 'Les données servent à créer votre compte, traiter vos demandes, assurer le suivi des devis, gérer les factures et vous contacter si nécessaire.',
            ],
            [
                'title' => '3. Sécurité',
                'content' => 'Les accès sont protégés par authentification, sessions sécurisées et contrôles applicatifs. Les mots de passe sont stockés sous forme chiffrée.',
            ],
            [
                'title' => '4. Conservation',
                'content' => 'Nous conservons les données pendant la durée nécessaire à la gestion de la relation client et aux obligations légales.',
            ],
            [
                'title' => '5. Vos droits',
                'content' => 'Vous pouvez demander l\'accès, la rectification ou la suppression de vos données en nous contactant.',
            ],
            [
                'title' => '6. Contact',
                'content' => 'Pour toute demande RGPD : 0603595028 - akamsamy69@gmail.com',
            ],
        ],
    ],
    'en' => [
        'title' => 'Data protection (GDPR)',
        'intro' => 'We implement technical and organizational measures to protect your personal data and keep its use limited to what is strictly necessary.',
        'sections' => [
            [
                'title' => '1. Data collected',
                'content' => 'We collect only the data needed for your account, quotes, invoices, and communication with the administration.',
            ],
            [
                'title' => '2. Purpose',
                'content' => 'Data is used to create your account, process requests, track quotes, manage invoices, and contact you when needed.',
            ],
            [
                'title' => '3. Security',
                'content' => 'Access is protected by authentication, secure sessions, and application-level checks. Passwords are stored in hashed form.',
            ],
            [
                'title' => '4. Retention',
                'content' => 'We keep data for as long as needed to manage the customer relationship and legal obligations.',
            ],
            [
                'title' => '5. Your rights',
                'content' => 'You may request access, correction, or deletion of your data by contacting us.',
            ],
            [
                'title' => '6. Contact',
                'content' => 'For any GDPR request: 0603595028 - akamsamy69@gmail.com',
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