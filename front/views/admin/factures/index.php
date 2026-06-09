<?php $factures = $factures ?? []; ?>
<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><span>F</span>actures</h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="admin-alert admin-alert-success"><?= e($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_dashboard') ?>">Dashboard admin</a>
            <a class="btn" href="<?= route('admin_factures_create') ?>">Ajouter une facture</a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Reference</th>
                        <th>Devis</th>
                        <th>Client</th>
                        <th>Statut</th>
                        <th>Montant TTC</th>
                        <th>Date de création de la facture</th>
                        <th>Date de création du devis</th>
                        <th>Date de réservation de l'événement</th>
                        <th>Emission</th>
                        <th>Echeance</th>
                        <th>Paiement</th>
                        <th>Envoi mail</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($factures)): ?>
                        <tr>
                            <td colspan="14">Aucune facture enregistree.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($factures as $f): ?>
                            <tr>
                                <td><?= (int) $f['id_facture'] ?></td>
                                <td><?= e($f['reference']) ?></td>
                                <td><?= e($f['devis_reference']) ?></td>
                                <td><?= e(trim(($f['client_prenom'] ?? '') . ' ' . ($f['client_nom'] ?? ''))) ?></td>
                                <td><?= e($f['statut']) ?></td>
                                <td><?= e(number_format((float) $f['montant_ttc'], 2, ',', ' ')) ?> EUR</td>
                                <td><?= !empty($f['facture_created_at']) ? e(date('d/m/Y', strtotime((string) $f['facture_created_at']))) : '-' ?></td>
                                <td><?= !empty($f['devis_created_at']) ? e(date('d/m/Y', strtotime((string) $f['devis_created_at']))) : '-' ?></td>
                                <td><?= !empty($f['date_reservation']) ? e(date('d/m/Y', strtotime((string) $f['date_reservation']))) : '-' ?></td>
                                <td><?= e($f['date_emission'] ?? '-') ?></td>
                                <td><?= e($f['date_echeance'] ?? '-') ?></td>
                                <td><?= e($f['date_paiement'] ?? '-') ?></td>
                                <td><?= e($f['date_envoi_mail'] ?? '-') ?></td>
                                <td class="admin-table-actions">
                                    <a class="admin-link" href="<?= route('admin_factures_show', ['id' => $f['id_facture']]) ?>">Consulter</a>
                                    <a class="admin-link" href="<?= route('admin_factures_edit', ['id' => $f['id_facture']]) ?>">Modifier</a>
                                    <?php if (($f['statut'] ?? '') !== 'envoyee'): ?>
                                        <form method="POST" action="<?= route('admin_factures_send_mail', ['id' => $f['id_facture']]) ?>">
                                            <button type="submit" class="admin-btn">Envoyer par mail</button>
                                        </form>
                                    <?php endif; ?>
                                    <form method="POST" action="<?= route('admin_factures_delete', ['id' => $f['id_facture']]) ?>" onsubmit="return confirm('Supprimer cette facture ?');">
                                        <button type="submit" class="admin-btn admin-btn-danger">Supprimer</button>
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