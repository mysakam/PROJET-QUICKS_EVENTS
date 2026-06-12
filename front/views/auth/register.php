<?php $langQuery = '?lang=' . ($lang ?? 'fr'); ?>

<section class="apropos auth-section">
    <div class="admin-media-shell auth-shell">
        <div class="auth-copy">
            <p class="auth-kicker">QUICK'EVENTS</p>
            <h2 class="titre-texte"><span>I</span>nscription</h2>
            <p>Créez votre compte client pour accéder à votre espace personnel et préparer ensuite votre fiche événement.</p>

            <div class="auth-highlights">
                <div class="auth-highlight">Compte créé en quelques étapes</div>
                <div class="auth-highlight">Accès direct aux catalogues</div>
                <div class="auth-highlight">Fiche événement après inscription</div>
            </div>

            <a class="btn auth-side-link" href="<?= route('login') . $langQuery ?>">J'ai déjà un compte</a>
        </div>

        <div class="admin-media-shell admin-form-shell auth-card">
            <h3 class="auth-card-title">Créer mon compte</h3>

            <?php if (!empty($_SESSION['error'])): ?>
                <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="admin-form" method="POST" action="<?= route('register_post') . $langQuery ?>">
                <div class="auth-form-grid">
                    <div class="admin-form-row">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required value="<?= e($oldRegister['nom'] ?? '') ?>">
                    </div>
                    <div class="admin-form-row">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required value="<?= e($oldRegister['prenom'] ?? '') ?>">
                    </div>
                </div>

                <div class="admin-form-row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required value="<?= e($oldRegister['email'] ?? '') ?>">
                </div>
                <div class="admin-form-row">
                    <label for="telephone">Téléphone</label>
                    <input type="text" id="telephone" name="telephone" value="<?= e($oldRegister['telephone'] ?? '') ?>">
                </div>
                <div class="admin-form-row">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="admin-form-row">
                    <label for="password_confirm">Confirmer le mot de passe</label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>

                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit">Créer mon compte</button>
                    <a class="btn" href="<?= route('home') . $langQuery ?>">Retour accueil</a>
                </div>
            </form>
        </div>
    </div>
</section>