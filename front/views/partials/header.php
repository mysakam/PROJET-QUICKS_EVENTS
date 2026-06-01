<header style="padding:20px; border-bottom:1px solid #ddd; margin-bottom:20px;">
    <nav style="display:flex; gap:15px; flex-wrap:wrap;">
        <a href="<?= route('home') ?>">Accueil</a>
        <a href="<?= route('catalogues') ?>">Catalogues</a>

        <?php if (!empty($_SESSION['client'])): ?>
            <a href="<?= route('panier') ?>">Panier</a>
            <a href="<?= route('devis_index') ?>">Mes devis</a>
            <a href="<?= route('account') ?>">Mon compte</a>
            <a href="<?= route('logout') ?>">Deconnexion</a>
        <?php else: ?>
            <a href="<?= route('login') ?>">Connexion</a>
            <a href="<?= route('register') ?>">Inscription</a>
        <?php endif; ?>
    </nav>
</header>