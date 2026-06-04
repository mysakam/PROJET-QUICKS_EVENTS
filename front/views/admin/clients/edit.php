<?php $client = $client ?? []; ?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>M</span>odifier un client</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_clients_update', ['id' => $client['id_client']]) ?>">
            <div class="admin-form-row"><label for="nom">Nom</label><input id="nom" name="nom" type="text" value="<?= e($client['nom'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="prenom">Prenom</label><input id="prenom" name="prenom" type="text" value="<?= e($client['prenom'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="email">Email</label><input id="email" name="email" type="email" value="<?= e($client['email'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="telephone">Telephone</label><input id="telephone" name="telephone" type="text" value="<?= e($client['telephone'] ?? '') ?>"></div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit">Mettre a jour</button>
                <a class="btn" href="<?= route('admin_clients_index') ?>">Retour</a>
            </div>
        </form>
    </div>
</section>