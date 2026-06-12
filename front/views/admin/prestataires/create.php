<?php $categories = $categories ?? []; ?>
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
            <div class="admin-form-row"><label for="telephone">Téléphone</label><input id="telephone" name="telephone" type="text"></div>
            <div class="admin-form-row"><label for="adresse">Adresse</label><input id="adresse" name="adresse" type="text"></div>
            <div class="admin-form-row"><label for="type_evenement">Type d'événement proposé</label><input id="type_evenement" name="type_evenement" type="text" placeholder="mariage, séminaire, anniversaire..."></div>
            <div class="admin-form-row"><label for="capacite_max">Nombre de personnes (capacité max)</label><input id="capacite_max" name="capacite_max" type="number" min="1"></div>
            <div class="admin-form-row"><label for="prix_offre">Prix de l'offre</label><input id="prix_offre" name="prix_offre" type="text" placeholder="ex: 1200 EUR"></div>
            <?php
            $prestationsFormData = [[
                'id_prestation' => 0,
                'id_categorie' => 0,
                'nom' => '',
                'description' => '',
                'prix_unitaire' => '',
            ]];
            require __DIR__ . '/_prestations_form.php';
            ?>
            <div class="admin-form-row"><label for="iban">IBAN</label><input id="iban" name="iban" type="text" placeholder="FR76..."></div>
            <div class="admin-form-row"><label for="bic">BIC / SWIFT</label><input id="bic" name="bic" type="text" placeholder="AGRIFRPP"></div>
            <div class="admin-form-row"><label for="banque_nom">Nom de la banque</label><input id="banque_nom" name="banque_nom" type="text"></div>
            <div class="admin-form-row"><label for="titulaire_compte">Titulaire du compte</label><input id="titulaire_compte" name="titulaire_compte" type="text"></div>
            <div class="admin-form-row"><label for="note_sur_10">Note sur 10</label><input id="note_sur_10" name="note_sur_10" type="number" step="0.1" min="0" max="10" placeholder="8.5"></div>
            <div class="admin-form-row"><label for="description">Description de l'offre</label><textarea id="description" name="description" rows="4"></textarea></div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit">Enregistrer</button>
                <a class="btn" href="<?= route('admin_prestataires_index') ?>">Retour</a>
            </div>
        </form>
    </div>
</section>