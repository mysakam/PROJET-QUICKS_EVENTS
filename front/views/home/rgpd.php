<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));

$t = [
    'fr' => [
        'title' => 'Politique de confidentialité et protection des données',
        'intro' => 'La présente politique de confidentialité décrit les conditions dans lesquelles QUICK\'EVENTS collecte, utilise, conserve et protège les données personnelles des utilisateurs.',
        'sections' => [
            [
                'title' => '1. Responsable du traitement',
                'content' => 'Le responsable du traitement est QUICK\'EVENTS. Pour toute question relative à la protection de vos données, vous pouvez utiliser les coordonnées de contact indiquées ci-dessous.',
            ],
            [
                'title' => '2. Données collectées',
                'content' => 'Nous collectons uniquement les données strictement nécessaires à la gestion du compte, des devis, des factures, des échanges de suivi et, le cas échéant, des obligations légales applicables.',
            ],
            [
                'title' => '3. Finalités du traitement',
                'content' => 'Les données sont traitées afin de créer et administrer votre compte, instruire vos demandes, établir et suivre les devis et factures, assurer la relation client et répondre aux obligations légales.',
            ],
            [
                'title' => '4. Bases légales',
                'content' => 'Les traitements reposent selon les cas sur l\'exécution du contrat, le respect d\'obligations légales, l\'intérêt légitime de QUICK\'EVENTS ou votre consentement lorsque celui-ci est requis.',
            ],
            [
                'title' => '5. Destinataires des données',
                'content' => 'Les données sont destinées aux services internes habilités et, lorsque cela est nécessaire, aux prestataires techniques intervenant pour le fonctionnement du service.',
            ],
            [
                'title' => '6. Durée de conservation',
                'content' => 'Les données sont conservées pendant la durée nécessaire aux finalités décrites ci-dessus, puis archivées ou supprimées conformément aux obligations légales et aux délais de prescription applicables.',
            ],
            [
                'title' => '7. Sécurité',
                'content' => 'QUICK\'EVENTS met en œuvre des mesures techniques et organisationnelles destinées à préserver la confidentialité, l\'intégrité et la disponibilité des données, notamment l\'authentification, la gestion de session et le chiffrement des mots de passe.',
            ],
            [
                'title' => '8. Vos droits',
                'content' => 'Conformément à la réglementation applicable, vous disposez de droits d\'accès, de rectification, d\'effacement, d\'opposition, de limitation et de portabilité, sous réserve des conditions prévues par la loi.',
            ],
            [
                'title' => '9. Cookies et traceurs',
                'content' => 'Lorsque des cookies ou traceurs sont utilisés, ils servent uniquement au bon fonctionnement du service, à la sécurité de la navigation et, le cas échéant, à des mesures d\'audience strictement limitées.',
            ],
            [
                'title' => '10. Contact',
                'content' => 'Pour toute demande relative à la protection des données : 0603595028 - akamsamy69@gmail.com',
            ],
        ],
    ],
    'en' => [
        'title' => 'Privacy policy and data protection',
        'intro' => "This privacy policy explains how QUICK'EVENTS collects, uses, stores, and protects users' personal data.",
        'sections' => [
            [
                'title' => '1. Data controller',
                'content' => "The data controller is QUICK'EVENTS. If you have any question about your personal data, you may use the contact details below.",
            ],
            [
                'title' => '2. Data collected',
                'content' => 'We collect only the data strictly necessary to manage accounts, quotes, invoices, follow-up exchanges, and, where applicable, legal obligations.',
            ],
            [
                'title' => '3. Purposes of processing',
                'content' => 'Data is processed to create and administer your account, handle requests, prepare and follow up quotes and invoices, manage customer relations, and comply with legal obligations.',
            ],
            [
                'title' => '4. Legal bases',
                'content' => 'Depending on the case, processing is based on contract performance, legal obligation, legitimate interest of QUICK\'EVENTS, or your consent where required.',
            ],
            [
                'title' => '5. Data recipients',
                'content' => 'Data is intended for authorized internal teams and, where necessary, technical providers involved in running the service.',
            ],
            [
                'title' => '6. Retention period',
                'content' => 'Data is kept for as long as needed for the purposes described above and then archived or deleted in accordance with applicable legal and limitation periods.',
            ],
            [
                'title' => '7. Security',
                'content' => "QUICK'EVENTS implements technical and organizational measures to preserve data confidentiality, integrity, and availability, including authentication, session management, and password hashing.",
            ],
            [
                'title' => '8. Your rights',
                'content' => 'Under applicable law, you may exercise your rights of access, rectification, erasure, objection, restriction, and portability, subject to legal conditions.',
            ],
            [
                'title' => '9. Cookies and trackers',
                'content' => 'Where cookies or trackers are used, they are limited to service operation, browsing security, and, where applicable, strictly limited audience measurement.',
            ],
            [
                'title' => '10. Contact',
                'content' => 'For any privacy request: 0603595028 - akamsamy69@gmail.com',
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