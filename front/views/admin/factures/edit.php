<?php
$facture = $facture ?? [];
$statuses = $statuses ?? ['emise', 'envoyee', 'payee', 'annulee', 'en_retard'];
$lang = $lang ?? current_lang();

$statusLabel = static function (string $status) use ($lang): string {
    $key = 'admin.status.' . strtolower($status);
    $translated = t($key, $lang);
    return $translated !== $key ? $translated : ucfirst(str_replace('_', ' ', strtolower($status)));
};
?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><?= e(t('admin.factures.edit_title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_factures_update', ['id' => $facture['id_facture']]) ?>?lang=<?= e($lang) ?>">
            <div class="admin-form-row">
                <label><?= e(t('admin.factures.th_ref', $lang)) ?></label>
                <input type="text" value="<?= e($facture['reference'] ?? '') ?>" disabled>
            </div>
            <div class="admin-form-row">
                <label for="statut"><?= e(t('admin.factures.th_status', $lang)) ?></label>
                <select id="statut" name="statut" required>
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= e($status) ?>" <?= ($facture['statut'] ?? '') === $status ? 'selected' : '' ?>><?= e($statusLabel($status)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="admin-form-row">
                <label for="montant_ttc"><?= e(t('admin.factures.label_amount', $lang)) ?></label>
                <input id="montant_ttc" name="montant_ttc" type="text" value="<?= e((string) ($facture['montant_ttc'] ?? '0')) ?>" required>
            </div>
            <div class="admin-form-row">
                <label for="date_emission"><?= e(t('admin.factures.label_issue', $lang)) ?></label>
                <input id="date_emission" name="date_emission" type="date" value="<?= e($facture['date_emission'] ?? '') ?>">
            </div>
            <div class="admin-form-row">
                <label for="date_echeance"><?= e(t('admin.factures.label_due', $lang)) ?></label>
                <input id="date_echeance" name="date_echeance" type="date" value="<?= e($facture['date_echeance'] ?? '') ?>">
            </div>
            <div class="admin-form-row">
                <label for="date_paiement"><?= e(t('admin.factures.label_payment', $lang)) ?></label>
                <input id="date_paiement" name="date_paiement" type="date" value="<?= e($facture['date_paiement'] ?? '') ?>">
            </div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit"><?= e(t('admin.factures.update', $lang)) ?></button>
                <a class="btn" href="<?= route('admin_factures_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.back', $lang)) ?></a>
            </div>
        </form>
    </div>
</section>