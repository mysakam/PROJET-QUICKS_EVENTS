<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$devisList = $devisList ?? [];
$facturesByDevisId = $facturesByDevisId ?? [];

$txt = [
    'fr' => [
        'title' => 'Mon compte client',
        'nom' => 'Nom',
        'prenom' => 'Prénom',
        'email' => 'Email',
        'telephone' => 'Téléphone',
        'created' => 'Compte créé le',
        'catalogues' => 'Voir les catalogues',
        'devis' => 'Mes devis',
        'factures' => 'Mes factures',
        'track' => 'Suivi de mon parcours devis/factures',
        'devis_head' => 'Devis',
        'facture_head' => 'Facture',
        'ref_devis' => 'Référence devis',
        'statut_devis' => 'Statut devis',
        'date_devis' => 'Date devis',
        'montant_devis' => 'Montant devis',
        'ref_facture' => 'Référence facture',
        'statut_facture' => 'Statut facture',
        'date_facture' => 'Date facture',
        'montant_facture' => 'Montant facture (TTC)',
        'empty' => 'Aucun devis pour le moment.',
        'validated' => 'Validé',
    ],
    'en' => [
        'title' => 'My client account',
        'nom' => 'Last name',
        'prenom' => 'First name',
        'email' => 'Email',
        'telephone' => 'Phone',
        'created' => 'Account created on',
        'catalogues' => 'View catalogues',
        'devis' => 'My quotes',
        'factures' => 'My invoices',
        'track' => 'Quote/invoice journey',
        'devis_head' => 'Quote',
        'facture_head' => 'Invoice',
        'ref_devis' => 'Quote reference',
        'statut_devis' => 'Quote status',
        'date_devis' => 'Quote date',
        'montant_devis' => 'Quote amount',
        'ref_facture' => 'Invoice reference',
        'statut_facture' => 'Invoice status',
        'date_facture' => 'Invoice date',
        'montant_facture' => 'Invoice amount (incl. tax)',
        'empty' => 'No quotes yet.',
        'validated' => 'Validated',
    ],
];
$t = $txt[$lang];

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

    if (strtolower($normalized) === 'valide_client') {
        return $lang === 'fr' ? 'Validé' : 'Validated';
    }

    $label = str_replace('_', ' ', strtolower($normalized));
    return ucfirst($label);
};
?>

<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span><?= $lang === 'fr' ? 'M' : 'M' ?></span><?= $lang === 'fr' ? 'on compte client' : 'y client account' ?></h2>

        <div class="admin-table-wrap">
            <table class="admin-table" style="min-width: 0;">
                <tbody>
                    <tr>
                        <th><?= e($t['nom']) ?></th>
                        <td><?= e($client['nom'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th><?= e($t['prenom']) ?></th>
                        <td><?= e($client['prenom'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th><?= e($t['email']) ?></th>
                        <td><?= e($client['email'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th><?= e($t['telephone']) ?></th>
                        <td><?= e($client['telephone'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th><?= e($t['created']) ?></th>
                        <td><?= e($client['created_at'] ?? '-') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="admin-form-actions" style="margin-top: 18px;">
            <a class="btn" href="<?= route('catalogues') . '?lang=' . $lang ?>"><?= e($t['catalogues']) ?></a>
            <a class="btn" href="<?= route('devis_index') . '?lang=' . $lang ?>"><?= e($t['devis']) ?></a>
            <a class="btn" href="<?= route('factures_index') . '?lang=' . $lang ?>"><?= e($t['factures']) ?></a>
        </div>

        <h3 id="mes-factures" style="margin-top: 28px;"><?= e($t['track']) ?></h3>

        <div class="admin-table-wrap" style="margin-top: 10px;">
            <table class="admin-table account-journey-table" style="min-width: 0;">
                <thead>
                    <tr>
                        <th colspan="4"><?= e($t['devis_head']) ?></th>
                        <th colspan="4" class="account-journey-sep"><?= e($t['facture_head']) ?></th>
                    </tr>
                    <tr>
                        <th><?= e($t['ref_devis']) ?></th>
                        <th><?= e($t['statut_devis']) ?></th>
                        <th><?= e($t['date_devis']) ?></th>
                        <th><?= e($t['montant_devis']) ?></th>
                        <th class="account-journey-sep"><?= e($t['ref_facture']) ?></th>
                        <th><?= e($t['statut_facture']) ?></th>
                        <th><?= e($t['date_facture']) ?></th>
                        <th><?= e($t['montant_facture']) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devisList)): ?>
                        <tr>
                            <td colspan="8"><?= e($t['empty']) ?></td>
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