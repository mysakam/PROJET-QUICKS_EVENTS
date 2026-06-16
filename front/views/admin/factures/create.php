<?php
$devisOptions = $devisOptions ?? [];
$defaultReference = $defaultReference ?? '';
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
        <h2 class="titre-texte"><?= e(t('admin.factures.create_title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($devisOptions)): ?>
            <p><?= e(t('admin.factures.all_quoted', $lang)) ?></p>
            <a class="btn" href="<?= route('admin_factures_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.back', $lang)) ?></a>
        <?php else: ?>
            <form class="admin-form" method="POST" action="<?= route('admin_factures_store') ?>?lang=<?= e($lang) ?>">
                <div class="admin-form-row">
                    <label for="id_devis"><?= e(t('admin.factures.th_quote', $lang)) ?></label>
                    <select id="id_devis" name="id_devis" required>
                        <?php foreach ($devisOptions as $d): ?>
                            <option value="<?= (int) $d['id_devis'] ?>">
                                <?= e($d['reference']) ?> - <?= e(trim(($d['prenom'] ?? '') . ' ' . ($d['nom'] ?? ''))) ?> (<?= e(number_format((float) $d['montant_total'], 2, ',', ' ')) ?> EUR)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-row">
                    <label for="reference"><?= e(t('admin.factures.label_invoice_ref', $lang)) ?></label>
                    <input id="reference" name="reference" type="text" value="<?= e($defaultReference) ?>" required>
                </div>
                <div class="admin-form-row">
                    <label for="statut"><?= e(t('admin.factures.th_status', $lang)) ?></label>
                    <select id="statut" name="statut" required>
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= e($status) ?>"><?= e($statusLabel($status)) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="admin-form-row">
                    <label for="montant_ttc"><?= e(t('admin.factures.label_amount', $lang)) ?></label>
                    <input id="montant_ttc" name="montant_ttc" type="text" value="0" required>
                </div>
                <div class="admin-form-row">
                    <label for="date_emission"><?= e(t('admin.factures.label_issue', $lang)) ?></label>
                    <input id="date_emission" name="date_emission" type="date">
                </div>
                <div class="admin-form-row">
                    <label for="date_echeance"><?= e(t('admin.factures.label_due', $lang)) ?></label>
                    <input id="date_echeance" name="date_echeance" type="date">
                </div>
                <div class="admin-form-row">
                    <label for="date_paiement"><?= e(t('admin.factures.label_payment', $lang)) ?></label>
                    <input id="date_paiement" name="date_paiement" type="date">
                </div>
                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit"><?= e(t('admin.factures.save', $lang)) ?></button>
                    <a class="btn" href="<?= route('admin_factures_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.back', $lang)) ?></a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</section>