<?php
$prestataire = $prestataire ?? [];
$prestations = $prestations ?? [];
$activitySummary = $activitySummary ?? ['total_devis' => 0, 'total_factures' => 0, 'montant_factures' => 0.0];
$devisByStatus = $devisByStatus ?? [];
$facturesByStatus = $facturesByStatus ?? [];
$recentDevis = $recentDevis ?? [];
$recentFactures = $recentFactures ?? [];
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><span>F</span>iche prestataire</h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_prestataires_index') ?>">Retour liste</a>
            <a class="btn" href="<?= route('admin_prestataires_edit', ['id' => $prestataire['id_prestataire']]) ?>">Modifier</a>
        </div>

        <div class="admin-kpi-grid">
            <article class="admin-kpi-card">
                <h3>Devis</h3>
                <p><?= (int) $activitySummary['total_devis'] ?></p>
            </article>
            <article class="admin-kpi-card">
                <h3>Factures</h3>
                <p><?= (int) $activitySummary['total_factures'] ?></p>
            </article>
            <article class="admin-kpi-card">
                <h3>Montant facture</h3>
                <p><?= e(number_format((float) $activitySummary['montant_factures'], 2, ',', ' ')) ?> EUR</p>
            </article>
            <article class="admin-kpi-card">
                <h3>Note /10</h3>
                <p><?= e($prestataire['note_sur_10'] !== null && $prestataire['note_sur_10'] !== '' ? $prestataire['note_sur_10'] : '-') ?></p>
            </article>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <td><?= e($prestataire['nom'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= e($prestataire['email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Téléphone</th>
                        <td><?= e($prestataire['telephone'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Adresse</th>
                        <td><?= e($prestataire['adresse'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Type événement</th>
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
                        <th>Banque</th>
                        <td><?= e($prestataire['banque_nom'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Titulaire du compte</th>
                        <td><?= e($prestataire['titulaire_compte'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?= nl2br(e($prestataire['description'] ?? '-')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h3>Prestations associées</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Prestation</th>
                        <th>Catégorie</th>
                        <th>Prix unitaire</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestations)): ?>
                        <tr>
                            <td colspan="4">Aucune prestation associée.</td>
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

        <h3>Statuts des devis</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($devisByStatus)): ?>
                        <tr>
                            <td colspan="2">Aucun devis lié à ce prestataire.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($devisByStatus as $row): ?>
                            <tr>
                                <td><?= e($row['statut']) ?></td>
                                <td><?= (int) $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3>Statuts des factures</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Statut</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($facturesByStatus)): ?>
                        <tr>
                            <td colspan="2">Aucune facture liée à ce prestataire.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($facturesByStatus as $row): ?>
                            <tr>
                                <td><?= e($row['statut']) ?></td>
                                <td><?= (int) $row['total'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3>Derniers devis</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Reference</th>
                        <th>Statut</th>
                        <th>Montant prestataire</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentDevis)): ?>
                        <tr>
                            <td colspan="4">Aucun devis recent.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentDevis as $devis): ?>
                            <tr>
                                <td><?= e($devis['reference']) ?></td>
                                <td><?= e($devis['statut']) ?></td>
                                <td><?= e(number_format((float) $devis['montant_prestataire'], 2, ',', ' ')) ?> EUR</td>
                                <td><?= e($devis['created_at']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <h3>Dernières factures</h3>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Statut</th>
                        <th>Montant prestataire</th>
                        <th>Date émission</th>
                        <th>Date échéance</th>
                        <th>Date paiement</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentFactures)): ?>
                        <tr>
                            <td colspan="6">Aucune facture récente.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentFactures as $facture): ?>
                            <tr>
                                <td><?= e($facture['reference']) ?></td>
                                <td><?= e($facture['statut']) ?></td>
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