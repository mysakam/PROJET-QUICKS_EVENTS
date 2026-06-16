<?php
$facture = $facture ?? [];
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
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><?= e(t('admin.factures.show_title', $lang)) ?></h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_factures_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.factures.back_list', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_factures_edit', ['id' => $facture['id_facture']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.factures.edit', $lang)) ?></a>
        </div>

        <?php if (($facture['statut'] ?? '') !== 'envoyee'): ?>
            <div class="admin-invoice-send-panel">
                <div class="admin-invoice-send-header">
                    <h3><?= e(t('admin.factures.mail_title', $lang)) ?></h3>
                    <p><?= e(t('admin.factures.mail_text', $lang)) ?></p>
                </div>
                <form class="admin-invoice-send-form" method="POST" action="<?= route('admin_factures_send_mail', ['id' => $facture['id_facture']]) ?>?lang=<?= e($lang) ?>">
                    <div class="admin-form-row">
                        <label for="admin_message"><?= e(t('admin.factures.mail_message', $lang)) ?></label>
                        <textarea id="admin_message" name="admin_message" rows="4" placeholder="<?= e(t('admin.factures.mail_placeholder', $lang)) ?>"></textarea>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn"><?= e(t('admin.factures.send_mail', $lang)) ?></button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <tbody>
                    <tr>
                        <th><?= e(t('admin.factures.th_ref', $lang)) ?></th>
                        <td><?= e($facture['reference'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.th_status', $lang)) ?></th>
                        <td><?= e($statusLabel((string) ($facture['statut'] ?? ''))) ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.th_amount', $lang)) ?></th>
                        <td><?= e(number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.th_facture_created', $lang)) ?></th>
                        <td><?= !empty($facture['facture_created_at']) ? e(date('d/m/Y', strtotime((string) $facture['facture_created_at']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.th_quote_created', $lang)) ?></th>
                        <td><?= !empty($facture['devis_created_at']) ? e(date('d/m/Y', strtotime((string) $facture['devis_created_at']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.th_booking_date', $lang)) ?></th>
                        <td><?= !empty($facture['date_reservation']) ? e(date('d/m/Y', strtotime((string) $facture['date_reservation']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_issue', $lang)) ?></th>
                        <td><?= e($facture['date_emission'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_due', $lang)) ?></th>
                        <td><?= e($facture['date_echeance'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_payment', $lang)) ?></th>
                        <td><?= e($facture['date_paiement'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_mail_sent', $lang)) ?></th>
                        <td><?= e($facture['date_envoi_mail'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_quote', $lang)) ?></th>
                        <td><?= e($facture['devis_reference'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.th_client', $lang)) ?></th>
                        <td><?= e(trim(($facture['client_prenom'] ?? '') . ' ' . ($facture['client_nom'] ?? ''))) ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_client_email', $lang)) ?></th>
                        <td><?= e($facture['client_email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_quote_amount', $lang)) ?></th>
                        <td><?= e(number_format((float) ($facture['devis_montant_total'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_quote_status', $lang)) ?></th>
                        <td><?= e($statusLabel((string) ($facture['devis_statut'] ?? ''))) ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.factures.label_client_message', $lang)) ?></th>
                        <td><?= nl2br(e($facture['message_client'] ?? '-')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>