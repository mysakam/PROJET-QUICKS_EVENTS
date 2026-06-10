<?php
$devisList = $devisList ?? [];
$facturesByDevisId = $facturesByDevisId ?? [];

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

$statusLabel = static function (?string $status): string {
    $normalized = trim((string) $status);
    if ($normalized === '') {
        return '-';
    }

    if (strtolower($normalized) === 'valide_client') {
        return 'Validé';
    }

    $label = str_replace('_', ' ', strtolower($normalized));
    return ucfirst($label);
};
?>

<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>M</span>on compte client</h2>

        <div class="admin-table-wrap">
            <table class="admin-table" style="min-width: 0;">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <td><?= e($client['nom'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Prenom</th>
                        <td><?= e($client['prenom'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= e($client['email'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Telephone</th>
                        <td><?= e($client['telephone'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Compte cree le</th>
                        <td><?= e($client['created_at'] ?? '-') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="admin-form-actions" style="margin-top: 18px;">
            <a class="btn" href="<?= route('catalogues') ?>">Voir les catalogues</a>
            <a class="btn" href="<?= route('devis_index') ?>">Mes devis</a>
            <a class="btn" href="<?= route('factures_index') ?>">Mes factures</a>
        </div>

        <h3 id="mes-factures" style="margin-top: 28px;">Suivi de mon parcours devis/factures</h3>

        <div class="admin-table-wrap" style="margin-top: 10px;">
            <table class="admin-table account-journey-table" style="min-width: 0;">
                <thead>
                    <tr>
                        <th colspan="4">Devis</th>
                        <th colspan="4" class="account-journey-sep">Facture</th>
                    </tr>
                    <tr>
                        <th>Reference devis</th>
                        <th>Statut devis</th>
                        <th>Date devis</th>
                        <th>Montant devis</th>
                        <th class="account-journey-sep">Reference facture</th>
                        <th>Statut facture</th>
                        <th>Date facture</th>
                        <th>Montant facture (TTC)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devisList)): ?>
                        <tr>
                            <td colspan="8">Aucun devis pour le moment.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($devisList as $devis): ?>
                            <?php $facture = $facturesByDevisId[(int) ($devis['id_devis'] ?? 0)] ?? null; ?>
                            <?php $devisStatut = (string) ($devis['statut'] ?? ''); ?>
                            <?php $factureStatut = (string) ($facture['statut'] ?? ''); ?>
                            <tr>
                                <td><?= e($devis['reference'] ?? ('DEVIS #' . (int) ($devis['id_devis'] ?? 0))) ?></td>
                                <td><span class="status-pill <?= e($statusClass($devisStatut)) ?>"><?= e($statusLabel($devisStatut)) ?></span></td>
                                <td><?= !empty($devis['created_at']) ? e(date('d/m/Y', strtotime((string) $devis['created_at']))) : '-' ?></td>
                                <td><?= e(number_format((float) ($devis['montant_total'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                                <td class="account-journey-sep"><?= e($facture['reference'] ?? '-') ?></td>
                                <td>
                                    <?php if (!empty($facture)): ?>
                                        <span class="status-pill <?= e($statusClass($factureStatut)) ?>"><?= e($statusLabel($factureStatut)) ?></span>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= !empty($facture['created_at']) ? e(date('d/m/Y', strtotime((string) $facture['created_at']))) : '-' ?></td>
                                <td><?= isset($facture['montant_ttc']) ? e(number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ')) . ' EUR' : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>