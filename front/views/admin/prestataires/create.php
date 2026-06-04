<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>A</span>jouter un prestataire</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_prestataires_store') ?>">
            <div class="admin-form-row"><label for="nom">Nom</label><input id="nom" name="nom" type="text" required></div>
            <div class="admin-form-row"><label for="email">Email</label><input id="email" name="email" type="email"></div>
            <div class="admin-form-row"><label for="telephone">Telephone</label><input id="telephone" name="telephone" type="text"></div>
            <div class="admin-form-row"><label for="adresse">Adresse</label><input id="adresse" name="adresse" type="text"></div>
            <div class="admin-form-row"><label for="type_evenement">Type d'evenement propose</label><input id="type_evenement" name="type_evenement" type="text" placeholder="mariage, seminaire, anniversaire..."></div>
            <div class="admin-form-row"><label for="capacite_max">Nombre de personnes (capacite max)</label><input id="capacite_max" name="capacite_max" type="number" min="1"></div>
            <div class="admin-form-row"><label for="prix_offre">Prix de l'offre</label><input id="prix_offre" name="prix_offre" type="text" placeholder="ex: 1200 EUR"></div>
            <div class="admin-form-row"><label for="description">Description de l'offre</label><textarea id="description" name="description" rows="4"></textarea></div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit">Enregistrer</button>
                <a class="btn" href="<?= route('admin_prestataires_index') ?>">Retour</a>
            </div>
        </form>
    </div>
</section>