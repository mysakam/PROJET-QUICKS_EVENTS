<?php $prestataires = $prestataires ?? []; ?>
<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><span>P</span>restataires</h2>

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
            <a class="btn" href="<?= route('admin_prestataires_create') ?>">Ajouter un prestataire</a>
        </div>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Adresse</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestataires)): ?>
                        <tr>
                            <td colspan="6">Aucun prestataire enregistre.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prestataires as $p): ?>
                            <tr>
                                <td><?= (int) $p['id_prestataire'] ?></td>
                                <td><?= e($p['nom']) ?></td>
                                <td><?= e($p['email'] ?? '-') ?></td>
                                <td><?= e($p['telephone'] ?? '-') ?></td>
                                <td><?= e($p['adresse'] ?? '-') ?></td>
                                <td class="admin-table-actions">
                                    <a class="admin-link" href="<?= route('admin_prestataires_show', ['id' => $p['id_prestataire']]) ?>">Consulter</a>
                                    <a class="admin-link" href="<?= route('admin_prestataires_edit', ['id' => $p['id_prestataire']]) ?>">Modifier</a>
                                    <form method="POST" action="<?= route('admin_prestataires_delete', ['id' => $p['id_prestataire']]) ?>" onsubmit="return confirm('Supprimer ce prestataire ?');">
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