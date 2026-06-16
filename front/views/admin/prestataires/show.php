<?php
$prestataire = $prestataire ?? [];
$prestations = $prestations ?? [];
$activitySummary = $activitySummary ?? ['total_devis' => 0, 'total_factures' => 0, 'montant_factures' => 0.0];
$devisByStatus = $devisByStatus ?? [];
$facturesByStatus = $facturesByStatus ?? [];
$recentDevis = $recentDevis ?? [];
$recentFactures = $recentFactures ?? [];
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
        <h2 class="titre-texte"><?= e(t('admin.prestataires.show_title', $lang)) ?></h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_prestataires_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.back_list', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_prestataires_edit', ['id' => $prestataire['id_prestataire']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.edit', $lang)) ?></a>
        </div>

        <div class="admin-kpi-grid">
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_quotes', $lang)) ?></h3>
                <p><?= (int) $activitySummary['total_devis'] ?></p>
            </article>
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_invoices', $lang)) ?></h3>
                <p><?= (int) $activitySummary['total_factures'] ?></p>
            </article>
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_invoice_amount', $lang)) ?></h3>
                <p><?= e(number_format((float) $activitySummary['montant_factures'], 2, ',', ' ')) ?> EUR</p>
            </article>
            <article class="admin-kpi-card">
                <h3><?= e(t('admin.prestataires.kpi_rating', $lang)) ?></h3>
                <p><?= e($prestataire['note_sur_10'] !== null && $prestataire['note_sur_10'] !== '' ? $prestataire['note_sur_10'] : '-') ?></p>
            </article>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <tbody>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_name', $lang)) ?></th>
                        <td><?= e($prestataire['nom'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= e($prestataire['email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_phone', $lang)) ?></th>
                        <td><?= e($prestataire['telephone'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_address', $lang)) ?></th>
                        <td><?= e($prestataire['adresse'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_event_type', $lang)) ?></th>
                        <td><?= e($prestataire['type_evenement'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>IBAN</th>
                        <td><?= e($prestataire['iban'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>BIC / SWIFT</th>
                        <td><?= e($prestataire['bic'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_bank_name', $lang)) ?></th>
                        <td><?= e($prestataire['banque_nom'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.label_holder', $lang)) ?></th>
                        <td><?= e($prestataire['titulaire_compte'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_description', $lang)) ?></th>
                        <td><?= nl2br(e($prestataire['description'] ?? '-')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.associated_services', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_service', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.category', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_unit_price', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_description', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestations)): ?>
                        <tr>
                            <td colspan="4"><?= e(t('admin.prestataires.none_services', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prestations as $prestation): ?>
                            <tr>
                                <td><?= e($prestation['nom'] ?? '-') ?></td>
                                <td><?= e($prestation['category_name'] ?? '-') ?></td>
                                <td><?= e(number_format((float) ($prestation['prix_unitaire'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                                <td><?= nl2br(e($prestation['description'] ?? '-')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.quote_statuses', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_total', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devisByStatus)): ?>
                        <tr>
                            <td colspan="2"><?= e(t('admin.prestataires.none_quotes', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($devisByStatus as $row): ?>
                            <tr>
                                <td><?= e($statusLabel((string) ($row['statut'] ?? ''))) ?></td>
                                <td><?= (int) $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.invoice_statuses', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_total', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($facturesByStatus)): ?>
                        <tr>
                            <td colspan="2"><?= e(t('admin.prestataires.none_invoices', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($facturesByStatus as $row): ?>
                            <tr>
                                <td><?= e($statusLabel((string) ($row['statut'] ?? ''))) ?></td>
                                <td><?= (int) $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.recent_quotes', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_ref', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_provider_amount', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_date', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentDevis)): ?>
                        <tr>
                            <td colspan="4"><?= e(t('admin.prestataires.none_recent_quotes', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentDevis as $devis): ?>
                            <tr>
                                <td><?= e($devis['reference']) ?></td>
                                <td><?= e($statusLabel((string) ($devis['statut'] ?? ''))) ?></td>
                                <td><?= e(number_format((float) $devis['montant_prestataire'], 2, ',', ' ')) ?> EUR</td>
                                <td><?= e($devis['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3><?= e(t('admin.prestataires.recent_invoices', $lang)) ?></h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.prestataires.th_ref', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_status', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_provider_amount', $lang)) ?></th>
                        <th><?= e(t('admin.factures.label_issue', $lang)) ?></th>
                        <th><?= e(t('admin.factures.label_due', $lang)) ?></th>
                        <th><?= e(t('admin.factures.label_payment', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentFactures)): ?>
                        <tr>
                            <td colspan="6"><?= e(t('admin.prestataires.none_recent_invoices', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentFactures as $facture): ?>
                            <tr>
                                <td><?= e($facture['reference']) ?></td>
                                <td><?= e($statusLabel((string) ($facture['statut'] ?? ''))) ?></td>
                                <td><?= e(number_format((float) $facture['montant_prestataire'], 2, ',', ' ')) ?> EUR</td>
                                <td><?= e($facture['date_emission'] ?? '-') ?></td>
                                <td><?= e($facture['date_echeance'] ?? '-') ?></td>
                                <td><?= e($facture['date_paiement'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>