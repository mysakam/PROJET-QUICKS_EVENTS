<?php
$client = $client ?? [];
$history = $history ?? [];
$filters = $filters ?? [];
$devisStatusOptions = $devisStatusOptions ?? [];
$factureStatusOptions = $factureStatusOptions ?? [];
$totalDevis = (float) ($totalDevis ?? 0);
$totalFactures = (float) ($totalFactures ?? 0);
$lang = $lang ?? current_lang();

$statusClass = static function (?string $status): string {
    $normalized = strtolower(trim((string) $status));

    return match ($normalized) {
        'payee', 'validee', 'valide_client' => 'status-success',
        'envoyee', 'emise' => 'status-info',
        'annulee', 'en_retard' => 'status-danger',
        'en_attente', 'en_attente_validation' => 'status-warning',
        default => 'status-neutral',
    };
};

$statusLabel = static function (?string $status) use ($lang): string {
    $normalized = trim((string) $status);
    if ($normalized === '') {
        return '-';
    }

    $key = 'admin.status.' . strtolower($normalized);
    $translated = t($key, $lang);
    if ($translated !== $key) {
        return $translated;
    }

    $label = str_replace('_', ' ', strtolower($normalized));
    return ucfirst($label);
};
?>
<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><?= e(t('admin.clients.history_title', $lang)) ?></h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_clients_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.clients.back_list', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_clients_edit', ['id' => (int) ($client['id_client'] ?? 0)]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.clients.edit_client', $lang)) ?></a>
        </div>

        <div class="admin-table-wrap" style="margin-bottom: 16px;">
            <table class="admin-table" style="min-width: 0;">
                <tbody>
                    <tr>
                        <th><?= e(t('admin.clients.label_client', $lang)) ?></th>
                        <td><?= e(trim((string) (($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? '')))) ?></td>
                        <th>Email</th>
                        <td><?= e((string) ($client['email'] ?? '-')) ?></td>
                    </tr>
                    <tr>
                        <th><?= e(t('admin.clients.th_phone', $lang)) ?></th>
                        <td><?= e((string) ($client['telephone'] ?? '-')) ?></td>
                        <th>ID</th>
                        <td><?= (int) ($client['id_client'] ?? 0) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <form class="admin-filter-form" method="GET" action="<?= route('admin_clients_show', ['id' => (int) ($client['id_client'] ?? 0)]) ?>">
            <input type="hidden" name="lang" value="<?= e($lang) ?>">
            <label for="devis_statut"><?= e(t('admin.clients.th_quote_status', $lang)) ?></label>
            <select id="devis_statut" name="devis_statut">
                <option value=""><?= e(t('admin.clients.all_m', $lang)) ?></option>
                <?php foreach ($devisStatusOptions as $status): ?>
                    <option value="<?= e((string) $status) ?>" <?= (($filters['devis_statut'] ?? '') === (string) $status) ? 'selected' : '' ?>>
                        <?= e($statusLabel((string) $status)) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="facture_statut"><?= e(t('admin.clients.th_invoice_status', $lang)) ?></label>
            <select id="facture_statut" name="facture_statut">
                <option value=""><?= e(t('admin.clients.all_f', $lang)) ?></option>
                <option value="__none__" <?= (($filters['facture_statut'] ?? '') === '__none__') ? 'selected' : '' ?>><?= e(t('admin.clients.without_invoice', $lang)) ?></option>
                <?php foreach ($factureStatusOptions as $status): ?>
                    <option value="<?= e((string) $status) ?>" <?= (($filters['facture_statut'] ?? '') === (string) $status) ? 'selected' : '' ?>>
                        <?= e($statusLabel((string) $status)) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="date_from"><?= e(t('admin.clients.quote_from', $lang)) ?></label>
            <input id="date_from" name="date_from" type="date" value="<?= e((string) ($filters['date_from'] ?? '')) ?>">

            <label for="date_to"><?= e(t('admin.clients.to', $lang)) ?></label>
            <input id="date_to" name="date_to" type="date" value="<?= e((string) ($filters['date_to'] ?? '')) ?>">

            <button class="admin-btn" type="submit"><?= e(t('admin.clients.filter', $lang)) ?></button>
            <a class="btn" href="<?= route('admin_clients_show', ['id' => (int) ($client['id_client'] ?? 0)]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.clients.reset', $lang)) ?></a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th><?= e(t('admin.clients.th_quote_ref', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_quote_status', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_quote_date', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_quote_amount', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_invoice_ref', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_invoice_status', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_invoice_date', $lang)) ?></th>
                        <th><?= e(t('admin.clients.th_invoice_amount', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($history)): ?>
                        <tr>
                            <td colspan="8"><?= e(t('admin.clients.history_empty', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($history as $row): ?>
                            <?php $devisStatut = (string) ($row['devis_statut'] ?? ''); ?>
                            <?php $factureStatut = (string) ($row['facture_statut'] ?? ''); ?>
                            <tr>
                                <td><?= e((string) ($row['devis_reference'] ?? ('DEVIS #' . (int) ($row['id_devis'] ?? 0)))) ?></td>
                                <td><span class="status-pill <?= e($statusClass($devisStatut)) ?>"><?= e($statusLabel($devisStatut)) ?></span></td>
                                <td><?= !empty($row['devis_created_at']) ? e(date('d/m/Y', strtotime((string) $row['devis_created_at']))) : '-' ?></td>
                                <td><?= e(number_format((float) ($row['devis_montant_total'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                                <td><?= e((string) ($row['facture_reference'] ?? '-')) ?></td>
                                <td>
                                    <?php if (!empty($row['id_facture'])): ?>
                                        <span class="status-pill <?= e($statusClass($factureStatut)) ?>"><?= e($statusLabel($factureStatut)) ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= !empty($row['facture_created_at']) ? e(date('d/m/Y', strtotime((string) $row['facture_created_at']))) : '-' ?></td>
                                <td>
                                    <?php if ($row['facture_montant_ttc'] !== null): ?>
                                        <?= e(number_format((float) ($row['facture_montant_ttc'] ?? 0), 2, ',', ' ')) ?> EUR
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-table-wrap" style="margin-top: 16px;">
            <table class="admin-table" style="min-width: 0;">
                <tbody>
                    <tr>
                        <th><?= e(t('admin.clients.total_quotes', $lang)) ?></th>
                        <td><?= e(number_format($totalDevis, 2, ',', ' ')) ?> EUR</td>
                        <th><?= e(t('admin.clients.total_invoices', $lang)) ?></th>
                        <td><?= e(number_format($totalFactures, 2, ',', ' ')) ?> EUR</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>