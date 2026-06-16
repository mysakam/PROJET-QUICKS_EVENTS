<?php
$factures = $factures ?? [];
$lang = $lang ?? current_lang();

$statusLabel = static function (?string $status) use ($lang): string {
    $normalized = strtolower(trim((string) $status));
    if ($normalized === '') {
        return '-';
    }

    $key = 'admin.status.' . $normalized;
    $translated = t($key, $lang);
    if ($translated !== $key) {
        return $translated;
    }

    $label = str_replace('_', ' ', $normalized);
    return ucfirst($label);
};
?>
<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><?= e(t('admin.factures.title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="admin-alert admin-alert-success"><?= e($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_dashboard') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.dashboard.title', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_factures_create') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.factures.add', $lang)) ?></a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= e(t('admin.factures.th_ref', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_quote', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_client', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_amount', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_facture_created', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_quote_created', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_booking_date', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_issue', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_due', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_payment', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_mail_sent', $lang)) ?></th>
                        <th><?= e(t('admin.factures.th_actions', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($factures)): ?>
                        <tr>
                            <td colspan="14"><?= e(t('admin.factures.none', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($factures as $f): ?>
                            <tr>
                                <td><?= (int) $f['id_facture'] ?></td>
                                <td><?= e($f['reference']) ?></td>
                                <td><?= e($f['devis_reference']) ?></td>
                                <td><?= e(trim(($f['client_prenom'] ?? '') . ' ' . ($f['client_nom'] ?? ''))) ?></td>
                                <td><?= e($statusLabel((string) ($f['statut'] ?? ''))) ?></td>
                                <td><?= e(number_format((float) $f['montant_ttc'], 2, ',', ' ')) ?> EUR</td>
                                <td><?= !empty($f['facture_created_at']) ? e(date('d/m/Y', strtotime((string) $f['facture_created_at']))) : '-' ?></td>
                                <td><?= !empty($f['devis_created_at']) ? e(date('d/m/Y', strtotime((string) $f['devis_created_at']))) : '-' ?></td>
                                <td><?= !empty($f['date_reservation']) ? e(date('d/m/Y', strtotime((string) $f['date_reservation']))) : '-' ?></td>
                                <td><?= e($f['date_emission'] ?? '-') ?></td>
                                <td><?= e($f['date_echeance'] ?? '-') ?></td>
                                <td><?= e($f['date_paiement'] ?? '-') ?></td>
                                <td><?= e($f['date_envoi_mail'] ?? '-') ?></td>
                                <td class="admin-table-actions">
                                    <a class="admin-link" href="<?= route('admin_factures_show', ['id' => $f['id_facture']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.factures.view', $lang)) ?></a>
                                    <a class="admin-link" href="<?= route('admin_factures_edit', ['id' => $f['id_facture']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.factures.edit', $lang)) ?></a>
                                    <?php if (($f['statut'] ?? '') !== 'envoyee'): ?>
                                        <form method="POST" action="<?= route('admin_factures_send_mail', ['id' => $f['id_facture']]) ?>?lang=<?= e($lang) ?>">
                                            <button type="submit" class="admin-btn"><?= e(t('admin.factures.send_mail', $lang)) ?></button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" action="<?= route('admin_factures_delete', ['id' => $f['id_facture']]) ?>?lang=<?= e($lang) ?>" onsubmit="return confirm('<?= e(t('admin.factures.delete_confirm', $lang)) ?>');">
                                        <button type="submit" class="admin-btn admin-btn-danger"><?= e(t('admin.factures.delete', $lang)) ?></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>