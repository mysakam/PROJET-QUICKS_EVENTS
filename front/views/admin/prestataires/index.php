<?php
$prestataires = $prestataires ?? [];
$searchQuery = $searchQuery ?? '';
$categories = $categories ?? [];
$prestations = $prestations ?? [];
$typeEvenementOptions = $typeEvenementOptions ?? [];
$categoryId = (int) ($categoryId ?? 0);
$prestationId = (int) ($prestationId ?? 0);
$typeEvenement = $typeEvenement ?? '';
?>
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

        <form class="admin-filter-form" method="GET" action="<?= route('admin_prestataires_index') ?>">
            <label for="q">Recherche</label>
            <input id="q" name="q" type="text" value="<?= e($searchQuery) ?>" placeholder="Nom, email, telephone, adresse">
            <label for="type_evenement">Type d'evenement</label>
            <select id="type_evenement" name="type_evenement">
                <option value="">-- Tous --</option>
                <?php foreach ($typeEvenementOptions as $option): ?>
                    <option value="<?= e($option) ?>" <?= $typeEvenement === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="category_id">Categorie</label>
            <select id="category_id" name="category_id">
                <option value="">-- Toutes --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id_categorie'] ?>" <?= $categoryId === (int) $category['id_categorie'] ? 'selected' : '' ?>>
                        <?= e($category['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="prestation_id">Prestation</label>
            <select id="prestation_id" name="prestation_id">
                <option value="">-- Toutes --</option>
                <?php foreach ($prestations as $prestation): ?>
                    <option value="<?= (int) $prestation['id_prestation'] ?>" <?= $prestationId === (int) $prestation['id_prestation'] ? 'selected' : '' ?>>
                        <?= e($prestation['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="admin-btn" type="submit">Filtrer</button>
            <a class="btn" href="<?= route('admin_prestataires_index') ?>">Reinitialiser</a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Adresse</th>
                        <th>Type evenement</th>
                        <th>Categories</th>
                        <th>Prestations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestataires)): ?>
                        <tr>
                            <td colspan="9">Aucun prestataire enregistre.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prestataires as $p): ?>
                            <tr>
                                <td><?= (int) $p['id_prestataire'] ?></td>
                                <td><?= e($p['nom']) ?></td>
                                <td><?= e($p['email'] ?? '-') ?></td>
                                <td><?= e($p['telephone'] ?? '-') ?></td>
                                <td><?= e($p['adresse'] ?? '-') ?></td>
                                <td><?= e($p['type_evenement'] ?? '-') ?></td>
                                <td><?= e($p['categories_labels'] ?? '-') ?></td>
                                <td><?= e($p['prestations_labels'] ?? '-') ?></td>
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