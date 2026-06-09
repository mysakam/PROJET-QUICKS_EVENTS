<?php
$facture = $facture ?? [];
?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>F</span>iche facture</h2>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_factures_index') ?>">Retour liste</a>
            <a class="btn" href="<?= route('admin_factures_edit', ['id' => $facture['id_facture']]) ?>">Modifier</a>
        </div>

        <?php if (($facture['statut'] ?? '') !== 'envoyee'): ?>
            <div class="admin-invoice-send-panel">
                <div class="admin-invoice-send-header">
                    <h3>Envoi par mail</h3>
                    <p>Ce message partira avec la facture vers le client.</p>
                </div>
                <form class="admin-invoice-send-form" method="POST" action="<?= route('admin_factures_send_mail', ['id' => $facture['id_facture']]) ?>">
                    <div class="admin-form-row">
                        <label for="admin_message">Message admin pour l'envoi</label>
                        <textarea id="admin_message" name="admin_message" rows="4" placeholder="Ex: Bonjour, veuillez trouver votre facture ci-jointe."></textarea>
                    </div>
                    <div class="admin-form-actions">
                        <button type="submit" class="admin-btn">Envoyer par mail</button>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <tbody>
                    <tr>
                        <th>Reference</th>
                        <td><?= e($facture['reference'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Statut</th>
                        <td><?= e($facture['statut'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Montant TTC</th>
                        <td><?= e(number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                    </tr>
                    <tr>
                        <th>Date de création de la facture</th>
                        <td><?= !empty($facture['facture_created_at']) ? e(date('d/m/Y', strtotime((string) $facture['facture_created_at']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Date de création du devis</th>
                        <td><?= !empty($facture['devis_created_at']) ? e(date('d/m/Y', strtotime((string) $facture['devis_created_at']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Date de réservation de l'événement</th>
                        <td><?= !empty($facture['date_reservation']) ? e(date('d/m/Y', strtotime((string) $facture['date_reservation']))) : '-' ?></td>
                    </tr>
                    <tr>
                        <th>Date emission</th>
                        <td><?= e($facture['date_emission'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Date echeance</th>
                        <td><?= e($facture['date_echeance'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Date paiement</th>
                        <td><?= e($facture['date_paiement'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Date envoi mail</th>
                        <td><?= e($facture['date_envoi_mail'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Devis</th>
                        <td><?= e($facture['devis_reference'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Client</th>
                        <td><?= e(trim(($facture['client_prenom'] ?? '') . ' ' . ($facture['client_nom'] ?? ''))) ?></td>
                    </tr>
                    <tr>
                        <th>Email client</th>
                        <td><?= e($facture['client_email'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Montant devis</th>
                        <td><?= e(number_format((float) ($facture['devis_montant_total'] ?? 0), 2, ',', ' ')) ?> EUR</td>
                    </tr>
                    <tr>
                        <th>Statut devis</th>
                        <td><?= e($facture['devis_statut'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Message client</th>
                        <td><?= nl2br(e($facture['message_client'] ?? '-')) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>