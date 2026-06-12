<?php
$clients = $clients ?? [];
$searchQuery = $searchQuery ?? '';

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
    <div class="admin-media-shell">
        <h2 class="titre-texte"><span>C</span>lients</h2>

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
            <a class="btn" href="<?= route('admin_clients_create') ?>">Ajouter un client</a>
        </div>

        <form class="admin-filter-form" method="GET" action="<?= route('admin_clients_index') ?>">
            <label for="q">Recherche</label>
            <input id="q" name="q" type="text" value="<?= e($searchQuery) ?>" placeholder="Nom, prénom, email, téléphone">
            <button class="admin-btn" type="submit">Filtrer</button>
            <a class="btn" href="<?= route('admin_clients_index') ?>">Réinitialiser</a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Devis</th>
                        <th>Factures</th>
                        <th>Historique devis</th>
                        <th>Historique factures</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                        <tr>
                            <td colspan="10">Aucun client enregistré.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td><?= (int) $c['id_client'] ?></td>
                                <td><?= e($c['nom']) ?></td>
                                <td><?= e($c['prenom']) ?></td>
                                <td><?= e($c['email']) ?></td>
                                <td><?= e($c['telephone'] ?? '-') ?></td>
                                <td><?= (int) ($c['devis_count'] ?? 0) ?></td>
                                <td><?= (int) ($c['factures_count'] ?? 0) ?></td>
                                <td>
                                    <?php $devisHistory = $c['devis_history'] ?? []; ?>
                                    <?php if (!empty($devisHistory)): ?>
                                        <ul class="history-list">
                                            <?php foreach ($devisHistory as $devis): ?>
                                                <?php $devisStatut = (string) ($devis['statut'] ?? ''); ?>
                                                <li>
                                                    <span><?= e((string) ($devis['reference'] ?? ('DEVIS #' . (int) ($devis['id_devis'] ?? 0)))) ?></span>
                                                    <span class="status-pill <?= e($statusClass($devisStatut)) ?>"><?= e($statusLabel($devisStatut)) ?></span>
                                                    <?php if (!empty($devis['created_at'])): ?>
                                                        <small><?= e(date('d/m/Y', strtotime((string) $devis['created_at']))) ?></small>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php $facturesHistory = $c['factures_history'] ?? []; ?>
                                    <?php if (!empty($facturesHistory)): ?>
                                        <ul class="history-list">
                                            <?php foreach ($facturesHistory as $facture): ?>
                                                <?php $factureStatut = (string) ($facture['statut'] ?? ''); ?>
                                                <li>
                                                    <span><?= e((string) ($facture['reference'] ?? ('FACTURE #' . (int) ($facture['id_facture'] ?? 0)))) ?></span>
                                                    <span class="status-pill <?= e($statusClass($factureStatut)) ?>"><?= e($statusLabel($factureStatut)) ?></span>
                                                    <?php if (!empty($facture['created_at'])): ?>
                                                        <small><?= e(date('d/m/Y', strtotime((string) $facture['created_at']))) ?></small>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td class="admin-table-actions">
                                    <a class="admin-link" href="<?= route('admin_clients_show', ['id' => $c['id_client']]) ?>">Consulter</a>
                                    <a class="admin-link" href="<?= route('admin_clients_edit', ['id' => $c['id_client']]) ?>">Modifier</a>
                                    <form method="POST" action="<?= route('admin_clients_delete', ['id' => $c['id_client']]) ?>" onsubmit="return confirm('Supprimer ce client ?');">
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