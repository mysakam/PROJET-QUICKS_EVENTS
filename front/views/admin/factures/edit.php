<?php
$facture = $facture ?? [];
$statuses = $statuses ?? ['emise', 'envoyee', 'payee', 'annulee', 'en_retard'];
?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>M</span>odifier une facture</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_factures_update', ['id' => $facture['id_facture']]) ?>">
            <div class="admin-form-row">
                <label>Reference</label>
                <input type="text" value="<?= e($facture['reference'] ?? '') ?>" disabled>
            </div>
            <div class="admin-form-row">
                <label for="statut">Statut</label>
                <select id="statut" name="statut" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= e($status) ?>" <?= ($facture['statut'] ?? '') === $status ? 'selected' : '' ?>><?= e($status) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="admin-form-row">
                <label for="montant_ttc">Montant TTC</label>
                <input id="montant_ttc" name="montant_ttc" type="text" value="<?= e((string) ($facture['montant_ttc'] ?? '0')) ?>" required>
            </div>
            <div class="admin-form-row">
                <label for="date_emission">Date emission</label>
                <input id="date_emission" name="date_emission" type="date" value="<?= e($facture['date_emission'] ?? '') ?>">
            </div>
            <div class="admin-form-row">
                <label for="date_echeance">Date echeance</label>
                <input id="date_echeance" name="date_echeance" type="date" value="<?= e($facture['date_echeance'] ?? '') ?>">
            </div>
            <div class="admin-form-row">
                <label for="date_paiement">Date paiement</label>
                <input id="date_paiement" name="date_paiement" type="date" value="<?= e($facture['date_paiement'] ?? '') ?>">
            </div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit">Mettre a jour</button>
                <a class="btn" href="<?= route('admin_factures_index') ?>">Retour</a>
            </div>
        </form>
    </div>
</section>