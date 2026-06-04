<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>I</span>nscription client</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('register_post') ?>">
            <div class="admin-form-row">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="admin-form-row">
                <label for="prenom">Prenom</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="admin-form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="admin-form-row">
                <label for="telephone">Telephone</label>
                <input type="text" id="telephone" name="telephone">
            </div>
            <div class="admin-form-row">
                <label for="type_evenement">Type d'evenement souhaite</label>
                <input type="text" id="type_evenement" name="type_evenement" placeholder="mariage, anniversaire, seminaire...">
            </div>
            <div class="admin-form-row">
                <label for="nb_personnes">Nombre de personnes</label>
                <input type="number" id="nb_personnes" name="nb_personnes" min="1">
            </div>
            <div class="admin-form-row">
                <label for="budget">Budget estimatif</label>
                <input type="text" id="budget" name="budget" placeholder="ex: 5000 EUR">
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
                <button class="admin-btn" type="submit">Creer mon compte</button>
                <a class="btn" href="<?= route('login') ?>">J'ai deja un compte</a>
            </div>
        </form>
    </div>
</section>