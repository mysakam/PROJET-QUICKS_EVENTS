<?php
$devis = $devis ?? [];
$client = $client ?? null;
$lignes = $lignes ?? [];
$facture = $facture ?? null;
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

    return ucfirst(str_replace('_', ' ', $normalized));
};
?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte">Fiche devis</h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_dashboard') ?>?lang=<?= e($lang) ?>">Retour au dashboard</a>
            <?php if (!empty($facture['id_facture'])): ?>
                <a class="btn" href="<?= route('admin_factures_show', ['id' => (int) $facture['id_facture']]) ?>?lang=<?= e($lang) ?>">Voir la facture</a>
            <?php endif; ?>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <tbody>
                    <tr>
                        <th>Référence</th>
                        <td><?= e($devis['reference'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td><?= e($statusLabel((string) ($devis['statut'] ?? ''))) ?></td>
                    </tr>
                    <tr>
                        <th>Montant total</th>
                        <td><?= e(number_format((float) ($devis['montant_total'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                    </tr>
                    <tr>
                        <th>Date événement</th>
                        <td><?= e($devis['date_evenement'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Créé le</th>
                        <td><?= !empty($devis['created_at']) ? e(date('d/m/Y H:i', strtotime((string) $devis['created_at']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Client</th>
                        <td><?= e(trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''))) ?></td>
                    </tr>
                    <tr>
                        <th>Email client</th>
                        <td><?= e($client['email'] ?? '-') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="admin-table-wrap" style="margin-top: 24px;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Prestation</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lignes as $ligne): ?>
                        <tr>
                            <td><?= e($ligne['nom'] ?? '-') ?></td>
                            <td><?= e((string) ((int) ($ligne['quantite'] ?? 0))) ?></td>
                            <td><?= e(number_format((float) ($ligne['prix_unitaire'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                            <td><?= e(number_format((float) ($ligne['montant_ligne'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($lignes === []): ?>
                        <tr>
                            <td colspan="4">Aucune ligne associée.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if (!empty($facture)): ?>
            <div class="admin-table-wrap" style="margin-top: 24px;">
                <table class="admin-table">
                    <tbody>
                        <tr>
                            <th>Facture associée</th>
                            <td><?= e($facture['reference'] ?? '-') ?></td>
                        </tr>
                        <tr>
                            <th>Statut facture</th>
                            <td><?= e($statusLabel((string) ($facture['statut'] ?? ''))) ?></td>
                        </tr>
                        <tr>
                            <th>Montant TTC</th>
                            <td><?= e(number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</section>