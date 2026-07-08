<?php
$devis = $devis ?? [];
$client = $client ?? null;
$lignes = $lignes ?? [];
$facture = $facture ?? null;
$disponibiliteChecks = $disponibiliteChecks ?? [];
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

        <div class="admin-table-wrap" style="margin-top: 24px;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Prestation</th>
                        <th>Prestataire</th>
                        <th>Disponibilite a la date evenement</th>
                        <th>Commentaire</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($disponibiliteChecks === []): ?>
                        <tr>
                            <td colspan="4">Aucune information de disponibilite a verifier pour ce devis.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($disponibiliteChecks as $check): ?>
                            <tr>
                                <td><?= e($check['prestation_nom'] ?? '-') ?></td>
                                <td><?= e($check['prestataire_nom'] ?? '-') ?></td>
                                <td>
                                    <?php if (($check['statut'] ?? '') === 'indisponible'): ?>
                                        <strong style="color:#b00020;">INDISPONIBLE</strong>
                                    <?php elseif (($check['statut'] ?? '') === 'disponible'): ?>
                                        <strong style="color:#0b6e3f;">DISPONIBLE</strong>
                                    <?php else: ?>
                                        <strong style="color:#7a5a00;">NON RENSEIGNE</strong>
                                    <?php endif; ?>
                                </td>
                                <td><?= e($check['commentaire'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php
        $hasConflict = false;
        foreach ($disponibiliteChecks as $check) {
            if (($check['statut'] ?? '') === 'indisponible') {
                $hasConflict = true;
                break;
            }
        }
        ?>

        <?php if ($hasConflict): ?>
            <p style="margin-top:12px; color:#b00020;"><strong>Attention:</strong> au moins un prestataire est indisponible a cette date. Contact client recommande avant validation finale.</p>
        <?php elseif ($disponibiliteChecks !== []): ?>
            <p style="margin-top:12px; color:#0b6e3f;"><strong>OK:</strong> aucun blocage declare sur les disponibilites pour cette date.</p>
        <?php endif; ?>

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