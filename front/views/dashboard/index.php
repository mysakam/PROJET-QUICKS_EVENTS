<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$clientName = e($_SESSION['client']['prenom'] ?? $_SESSION['client']['nom'] ?? 'client');
$isAdmin = !empty($_SESSION['client']['is_admin']);
$activityStats = $activityStats ?? [];

$formatDate = static function (?string $value): string {
    if (!$value) {
        return '-';
    }

    $timestamp = strtotime($value);
    if ($timestamp === false) {
        return '-';
    }

    return date('d/m/Y', $timestamp);
};

$formatAmount = static function (float $value): string {
    return number_format($value, 2, ',', ' ') . ' EUR';
};

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
        'quotes_card' => 'Devis',
        'quotes_total' => 'devis créés',
        'quotes_pending' => 'en attente de traitement',
        'quotes_validated' => 'validés côté client',
        'quotes_last' => 'Dernier devis',
        'quotes_amount' => 'Montant cumulé devis',
        'invoices_card' => 'Factures',
        'invoices_total' => 'factures disponibles',
        'invoices_paid' => 'factures réglées',
        'invoices_last' => 'Dernière facture',
        'invoices_amount' => 'Montant cumulé factures',
        'activity_card' => 'Activité',
        'activity_empty' => 'Aucune activité enregistrée pour le moment.',
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
        'quotes_card' => 'Quotes',
        'quotes_total' => 'quotes created',
        'quotes_pending' => 'pending processing',
        'quotes_validated' => 'validated by client',
        'quotes_last' => 'Latest quote',
        'quotes_amount' => 'Cumulative quote amount',
        'invoices_card' => 'Invoices',
        'invoices_total' => 'available invoices',
        'invoices_paid' => 'paid invoices',
        'invoices_last' => 'Latest invoice',
        'invoices_amount' => 'Cumulative invoice amount',
        'activity_card' => 'Activity',
        'activity_empty' => 'No activity recorded yet.',
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
                <p class="dashboard-stat-label"><?= e($t['quotes_card']) ?></p>
                <strong><?= e((string) ($activityStats['devis_total'] ?? 0)) ?></strong>
                <span><?= e($t['quotes_total']) ?></span>
                <span><?= e((string) ($activityStats['devis_pending'] ?? 0)) ?> <?= e($t['quotes_pending']) ?></span>
                <span><?= e((string) ($activityStats['devis_validated'] ?? 0)) ?> <?= e($t['quotes_validated']) ?></span>
            </article>

            <article class="auth-card dashboard-stat-card">
                <p class="dashboard-stat-label"><?= e($t['invoices_card']) ?></p>
                <strong><?= e((string) ($activityStats['factures_total'] ?? 0)) ?></strong>
                <span><?= e($t['invoices_total']) ?></span>
                <span><?= e((string) ($activityStats['factures_paid'] ?? 0)) ?> <?= e($t['invoices_paid']) ?></span>
                <span><?= e($t['invoices_last']) ?> : <?= e($formatDate($activityStats['last_invoice_created_at'] ?? null)) ?></span>
            </article>

            <article class="auth-card dashboard-stat-card">
                <p class="dashboard-stat-label"><?= e($t['activity_card']) ?></p>
                <?php if (($activityStats['devis_total'] ?? 0) === 0 && ($activityStats['factures_total'] ?? 0) === 0): ?>
                    <strong>0</strong>
                    <span><?= e($t['activity_empty']) ?></span>
                <?php else: ?>
                    <strong><?= e($formatAmount((float) ($activityStats['devis_total_amount'] ?? 0))) ?></strong>
                    <span><?= e($t['quotes_amount']) ?></span>
                    <span><?= e($t['invoices_amount']) ?> : <?= e($formatAmount((float) ($activityStats['factures_total_amount'] ?? 0))) ?></span>
                    <span><?= e($t['quotes_last']) ?> : <?= e($formatDate($activityStats['last_quote_created_at'] ?? null)) ?></span>
                <?php endif; ?>
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