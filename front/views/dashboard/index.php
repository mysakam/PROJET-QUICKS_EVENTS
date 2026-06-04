<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>T</span>ableau de bord client</h2>
        <p>Bienvenue <?= e($_SESSION['client']['prenom'] ?? $_SESSION['client']['nom'] ?? 'client') ?>.</p>
        <div class="admin-form-actions">
            <a class="btn" href="<?= route('catalogues') ?>">Voir les catalogues</a>
            <a class="btn" href="<?= route('devis_index') ?>">Mes devis</a>
            <a class="btn" href="<?= route('account') ?>">Mon compte</a>
        </div>
    </div>
</section>