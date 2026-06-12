<?php
$clientName = e($_SESSION['client']['prenom'] ?? $_SESSION['client']['nom'] ?? 'client');
$isAdmin = !empty($_SESSION['client']['is_admin']);
?>

<section class="apropos dashboard-section">
    <div class="admin-media-shell dashboard-shell">
        <div class="dashboard-copy">
            <p class="auth-kicker">QUICK'EVENTS</p>
            <h2 class="titre-texte"><span>B</span>ienvenue <?= e($clientName) ?></h2>
            <p>Votre espace client regroupe vos catalogues, vos devis et vos informations personnelles dans une interface plus claire.</p>

            <div class="auth-highlights">
                <div class="auth-highlight">Accès rapide aux catalogues</div>
                <div class="auth-highlight">Suivi des devis en cours</div>
                <div class="auth-highlight">Gestion de votre compte</div>
            </div>

            <div class="admin-form-actions dashboard-actions">
                <a class="admin-btn" href="<?= route('catalogues') ?>">Voir les catalogues</a>
                <a class="btn" href="<?= route('devis_index') ?>">Mes devis</a>
                <a class="btn" href="<?= route('account') ?>">Mon compte</a>
            </div>
        </div>

        <div class="dashboard-panel">
            <article class="auth-card dashboard-stat-card">
                <p class="dashboard-stat-label">Compte</p>
                <strong><?= e($clientName) ?></strong>
                <span><?= e($_SESSION['client']['email'] ?? '') ?></span>
            </article>

            <article class="auth-card dashboard-stat-card">
                <p class="dashboard-stat-label">Espace</p>
                <strong>Client actif</strong>
                <span>Explorez les services et vos demandes.</span>
            </article>

            <article class="auth-card dashboard-stat-card dashboard-stat-card-wide">
                <p class="dashboard-stat-label">Raccourcis</p>
                <div class="dashboard-quick-links">
                    <a href="<?= route('catalogues') ?>">Parcourir les offres</a>
                    <a href="<?= route('devis_index') ?>">Relire mes devis</a>
                    <a href="<?= route('account') ?>">Mettre à jour mon profil</a>
                    <?php if ($isAdmin): ?>
                        <a href="<?= route('admin_dashboard') ?>">Ouvrir le dashboard admin</a>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </div>
</section>