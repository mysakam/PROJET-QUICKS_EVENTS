<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$clientName = e($_SESSION['client']['prenom'] ?? $_SESSION['client']['nom'] ?? 'client');
$isAdmin = !empty($_SESSION['client']['is_admin']);

$txt = [
    'fr' => [
        'welcome' => 'Bienvenue',
        'intro' => 'Votre espace client regroupe vos catalogues, vos devis et vos informations personnelles dans une interface plus claire.',
        'quick_catalogues' => 'Accès rapide aux catalogues',
        'quick_devis' => 'Suivi des devis en cours',
        'quick_account' => 'Gestion de votre compte',
        'catalogues_btn' => 'Voir les catalogues',
        'devis_btn' => 'Mes devis',
        'account_btn' => 'Mon compte',
        'account_card' => 'Compte',
        'space_card' => 'Espace',
        'space_value' => 'Client actif',
        'space_desc' => 'Explorez les services et vos demandes.',
        'shortcuts' => 'Raccourcis',
        'shortcut_catalogues' => 'Parcourir les offres',
        'shortcut_devis' => 'Relire mes devis',
        'shortcut_account' => 'Mettre à jour mon profil',
        'shortcut_admin' => 'Ouvrir le dashboard admin',
    ],
    'en' => [
        'welcome' => 'Welcome',
        'intro' => 'Your client area groups your catalogues, quotes and personal information in a clearer interface.',
        'quick_catalogues' => 'Quick access to catalogues',
        'quick_devis' => 'Track quotes in progress',
        'quick_account' => 'Manage your account',
        'catalogues_btn' => 'View catalogues',
        'devis_btn' => 'My quotes',
        'account_btn' => 'My account',
        'account_card' => 'Account',
        'space_card' => 'Area',
        'space_value' => 'Active client',
        'space_desc' => 'Browse services and your requests.',
        'shortcuts' => 'Shortcuts',
        'shortcut_catalogues' => 'Browse offers',
        'shortcut_devis' => 'Review my quotes',
        'shortcut_account' => 'Update my profile',
        'shortcut_admin' => 'Open admin dashboard',
    ],
];
$t = $txt[$lang];
?>

<section class="apropos dashboard-section">
    <div class="admin-media-shell dashboard-shell">
        <div class="dashboard-copy">
            <p class="auth-kicker">QUICK'EVENTS</p>
            <h2 class="titre-texte"><?= e($t['welcome']) ?> <?= e($clientName) ?></h2>
            <p><?= e($t['intro']) ?></p>

            <div class="auth-highlights">
                <div class="auth-highlight"><?= e($t['quick_catalogues']) ?></div>
                <div class="auth-highlight"><?= e($t['quick_devis']) ?></div>
                <div class="auth-highlight"><?= e($t['quick_account']) ?></div>
            </div>

            <div class="admin-form-actions dashboard-actions">
                <a class="admin-btn" href="<?= route('catalogues') . '?lang=' . $lang ?>"><?= e($t['catalogues_btn']) ?></a>
                <a class="btn" href="<?= route('devis_index') . '?lang=' . $lang ?>"><?= e($t['devis_btn']) ?></a>
                <a class="btn" href="<?= route('account') . '?lang=' . $lang ?>"><?= e($t['account_btn']) ?></a>
            </div>
        </div>

        <div class="dashboard-panel">
            <article class="auth-card dashboard-stat-card">
                <p class="dashboard-stat-label"><?= e($t['account_card']) ?></p>
                <strong><?= e($clientName) ?></strong>
                <span><?= e($_SESSION['client']['email'] ?? '') ?></span>
            </article>

            <article class="auth-card dashboard-stat-card">
                <p class="dashboard-stat-label"><?= e($t['space_card']) ?></p>
                <strong><?= e($t['space_value']) ?></strong>
                <span><?= e($t['space_desc']) ?></span>
            </article>

            <article class="auth-card dashboard-stat-card dashboard-stat-card-wide">
                <p class="dashboard-stat-label"><?= e($t['shortcuts']) ?></p>
                <div class="dashboard-quick-links">
                    <a href="<?= route('catalogues') . '?lang=' . $lang ?>"><?= e($t['shortcut_catalogues']) ?></a>
                    <a href="<?= route('devis_index') . '?lang=' . $lang ?>"><?= e($t['shortcut_devis']) ?></a>
                    <a href="<?= route('account') . '?lang=' . $lang ?>"><?= e($t['shortcut_account']) ?></a>
                    <?php if ($isAdmin): ?>
                        <a href="<?= route('admin_dashboard') . '?lang=' . $lang ?>"><?= e($t['shortcut_admin']) ?></a>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </div>
</section>