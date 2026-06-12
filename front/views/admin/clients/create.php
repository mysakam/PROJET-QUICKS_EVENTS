<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>A</span>jouter un client</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_clients_store') ?>">
            <div class="admin-form-row"><label for="nom">Nom</label><input id="nom" name="nom" type="text" required></div>
            <div class="admin-form-row"><label for="prenom">Prénom</label><input id="prenom" name="prenom" type="text" required></div>
            <div class="admin-form-row"><label for="email">Email</label><input id="email" name="email" type="email" required></div>
            <div class="admin-form-row"><label for="telephone">Téléphone</label><input id="telephone" name="telephone" type="text"></div>
            <div class="admin-form-row"><label for="password">Mot de passe</label><input id="password" name="password" type="password" required></div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit">Enregistrer</button>
                <a class="btn" href="<?= route('admin_clients_index') ?>">Retour</a>
            </div>
        </form>
    </div>
</section>