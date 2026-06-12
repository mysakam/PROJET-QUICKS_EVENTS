<?php $langQuery = '?lang=' . ($lang ?? 'fr'); ?>

<section class="apropos auth-section">
    <div class="admin-media-shell auth-shell">
        <div class="auth-copy">
            <p class="auth-kicker">QUICK'EVENTS</p>
            <h2 class="titre-texte"><span>C</span>onnexion</h2>
            <p>Accédez à votre espace client pour suivre vos devis, votre panier et votre compte.</p>

            <div class="auth-highlights">
                <div class="auth-highlight">Catalogue et devis centralisés</div>
                <div class="auth-highlight">Historique de votre compte</div>
                <div class="auth-highlight">Accès rapide à vos actions</div>
            </div>

            <a class="btn auth-side-link" href="<?= route('register') . $langQuery ?>">Créer un compte</a>
        </div>

        <div class="admin-media-shell admin-form-shell auth-card">
            <h3 class="auth-card-title">Se connecter</h3>

            <?php if (!empty($_SESSION['error'])): ?>
                <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="admin-form" action="<?= route('login_post') . $langQuery ?>" method="post" data-fetch-form>
                <div class="admin-form-row">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required value="<?= e($oldLoginEmail ?? '') ?>">
                </div>

                <div class="admin-form-row">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit">Se connecter</button>
                    <a class="btn" href="<?= route('home') . $langQuery ?>">Retour accueil</a>
                </div>
            </form>
        </div>
    </div>
</section>