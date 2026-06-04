<?php $clients = $clients ?? []; ?>
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

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clients)): ?>
                        <tr>
                            <td colspan="6">Aucun client enregistre.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clients as $c): ?>
                            <tr>
                                <td><?= (int) $c['id_client'] ?></td>
                                <td><?= e($c['nom']) ?></td>
                                <td><?= e($c['prenom']) ?></td>
                                <td><?= e($c['email']) ?></td>
                                <td><?= e($c['telephone'] ?? '-') ?></td>
                                <td class="admin-table-actions">
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