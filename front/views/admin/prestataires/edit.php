<?php
$prestataire = $prestataire ?? [];
$categories = $categories ?? [];
$prestations = $prestations ?? [];
$prestationsFormData = $prestations !== [] ? $prestations : [[
    'id_prestation' => 0,
    'id_categorie' => 0,
    'nom' => '',
    'description' => '',
    'prix_unitaire' => '',
]];
$lang = $lang ?? current_lang();
?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><?= e(t('admin.prestataires.edit_title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_prestataires_update', ['id' => $prestataire['id_prestataire']]) ?>?lang=<?= e($lang) ?>">
            <div class="admin-form-row"><label for="nom"><?= e(t('admin.prestataires.label_name', $lang)) ?></label><input id="nom" name="nom" type="text" value="<?= e($prestataire['nom'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="email"><?= e(t('admin.prestataires.label_email', $lang)) ?></label><input id="email" name="email" type="email" value="<?= e($prestataire['email'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="telephone"><?= e(t('admin.prestataires.label_phone', $lang)) ?></label><input id="telephone" name="telephone" type="text" value="<?= e($prestataire['telephone'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="adresse"><?= e(t('admin.prestataires.label_address', $lang)) ?></label><input id="adresse" name="adresse" type="text" value="<?= e($prestataire['adresse'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="type_evenement"><?= e(t('admin.prestataires.label_event_type', $lang)) ?></label><input id="type_evenement" name="type_evenement" type="text" value="<?= e($prestataire['type_evenement'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="capacite_max"><?= e(t('admin.prestataires.label_capacity', $lang)) ?></label><input id="capacite_max" name="capacite_max" type="number" min="1"></div>
            <div class="admin-form-row"><label for="prix_offre"><?= e(t('admin.prestataires.label_offer_price', $lang)) ?></label><input id="prix_offre" name="prix_offre" type="text" placeholder="ex: 1200 EUR"></div>
            <?php require __DIR__ . '/_prestations_form.php'; ?>
            <div class="admin-form-row"><label for="iban"><?= e(t('admin.prestataires.label_iban', $lang)) ?></label><input id="iban" name="iban" type="text" value="<?= e($prestataire['iban'] ?? '') ?>" placeholder="FR76..."></div>
            <div class="admin-form-row"><label for="bic"><?= e(t('admin.prestataires.label_bic', $lang)) ?></label><input id="bic" name="bic" type="text" value="<?= e($prestataire['bic'] ?? '') ?>" placeholder="AGRIFRPP"></div>
            <div class="admin-form-row"><label for="banque_nom"><?= e(t('admin.prestataires.label_bank', $lang)) ?></label><input id="banque_nom" name="banque_nom" type="text" value="<?= e($prestataire['banque_nom'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="titulaire_compte"><?= e(t('admin.prestataires.label_holder', $lang)) ?></label><input id="titulaire_compte" name="titulaire_compte" type="text" value="<?= e($prestataire['titulaire_compte'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="note_sur_10"><?= e(t('admin.prestataires.label_rating', $lang)) ?></label><input id="note_sur_10" name="note_sur_10" type="number" step="0.1" min="0" max="10" value="<?= e($prestataire['note_sur_10'] ?? '') ?>"></div>
            <div class="admin-form-row"><label for="description"><?= e(t('admin.prestataires.label_offer_desc', $lang)) ?></label><textarea id="description" name="description" rows="4"><?= e($prestataire['description'] ?? '') ?></textarea></div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit"><?= e(t('admin.prestataires.update', $lang)) ?></button>
                <a class="btn" href="<?= route('admin_prestataires_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.back', $lang)) ?></a>
            </div>
        </form>
    </div>
</section>