<?php $prestataire = $prestataire ?? []; ?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>M</span>odifier un prestataire</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_prestataires_update', ['id' => $prestataire['id_prestataire']]) ?>">
            <div class="admin-form-row"><label for="nom">Nom</label><input id="nom" name="nom" type="text" value="<?= e($prestataire['nom'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="email">Email</label><input id="email" name="email" type="email" value="<?= e($prestataire['email'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="telephone">Telephone</label><input id="telephone" name="telephone" type="text" value="<?= e($prestataire['telephone'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="adresse">Adresse</label><input id="adresse" name="adresse" type="text" value="<?= e($prestataire['adresse'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="type_evenement">Type d'evenement propose</label><input id="type_evenement" name="type_evenement" type="text"></div>
            <div class="admin-form-row"><label for="capacite_max">Nombre de personnes (capacite max)</label><input id="capacite_max" name="capacite_max" type="number" min="1"></div>
            <div class="admin-form-row"><label for="prix_offre">Prix de l'offre</label><input id="prix_offre" name="prix_offre" type="text" placeholder="ex: 1200 EUR"></div>
            <div class="admin-form-row"><label for="iban">IBAN</label><input id="iban" name="iban" type="text" value="<?= e($prestataire['iban'] ?? '') ?>" placeholder="FR76..."></div>
            <div class="admin-form-row"><label for="bic">BIC / SWIFT</label><input id="bic" name="bic" type="text" value="<?= e($prestataire['bic'] ?? '') ?>" placeholder="AGRIFRPP"></div>
            <div class="admin-form-row"><label for="banque_nom">Nom de la banque</label><input id="banque_nom" name="banque_nom" type="text" value="<?= e($prestataire['banque_nom'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="titulaire_compte">Titulaire du compte</label><input id="titulaire_compte" name="titulaire_compte" type="text" value="<?= e($prestataire['titulaire_compte'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="note_sur_10">Note sur 10</label><input id="note_sur_10" name="note_sur_10" type="number" step="0.1" min="0" max="10" value="<?= e($prestataire['note_sur_10'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="description">Description de l'offre</label><textarea id="description" name="description" rows="4"><?= e($prestataire['description'] ?? '') ?></textarea></div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit">Mettre a jour</button>
                <a class="btn" href="<?= route('admin_prestataires_index') ?>">Retour</a>
            </div>
        </form>
    </div>
</section>