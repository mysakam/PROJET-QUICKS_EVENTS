<?php
$prestataires = $prestataires ?? [];
$searchQuery = $searchQuery ?? '';
$categories = $categories ?? [];
$prestations = $prestations ?? [];
$typeEvenementOptions = $typeEvenementOptions ?? [];
$categoryId = (int) ($categoryId ?? 0);
$prestationId = (int) ($prestationId ?? 0);
$typeEvenement = $typeEvenement ?? '';
$lang = $lang ?? current_lang();
?>
<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><?= e(t('admin.prestataires.title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="admin-alert admin-alert-success"><?= e($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_dashboard') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.dashboard.title', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_prestataires_create') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.add', $lang)) ?></a>
        </div>

        <form class="admin-filter-form" method="GET" action="<?= route('admin_prestataires_index') ?>">
            <input type="hidden" name="lang" value="<?= e($lang) ?>">
            <label for="q"><?= e(t('admin.prestataires.search', $lang)) ?></label>
            <input id="q" name="q" type="text" value="<?= e($searchQuery) ?>" placeholder="<?= e(t('admin.prestataires.search_ph', $lang)) ?>">
            <label for="type_evenement"><?= e(t('admin.prestataires.event_type', $lang)) ?></label>
            <select id="type_evenement" name="type_evenement">
                <option value=""><?= e(t('admin.prestataires.all', $lang)) ?></option>
                <?php foreach ($typeEvenementOptions as $option): ?>
                    <option value="<?= e($option) ?>" <?= $typeEvenement === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="category_id"><?= e(t('admin.prestataires.category', $lang)) ?></label>
            <select id="category_id" name="category_id">
                <option value=""><?= e(t('admin.prestataires.all_f', $lang)) ?></option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int) $category['id_categorie'] ?>" <?= $categoryId === (int) $category['id_categorie'] ? 'selected' : '' ?>>
                        <?= e($category['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="prestation_id"><?= e(t('admin.prestataires.prestation', $lang)) ?></label>
            <select id="prestation_id" name="prestation_id">
                <option value=""><?= e(t('admin.prestataires.all_f', $lang)) ?></option>
                <?php foreach ($prestations as $prestation): ?>
                    <option value="<?= (int) $prestation['id_prestation'] ?>" <?= $prestationId === (int) $prestation['id_prestation'] ? 'selected' : '' ?>>
                        <?= e($prestation['nom']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button class="admin-btn" type="submit"><?= e(t('admin.prestataires.filter', $lang)) ?></button>
            <a class="btn" href="<?= route('admin_prestataires_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.reset', $lang)) ?></a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= e(t('admin.prestataires.th_name', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_email', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_phone', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_address', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_event_type', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_categories', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_services', $lang)) ?></th>
                        <th><?= e(t('admin.prestataires.th_actions', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestataires)): ?>
                        <tr>
                            <td colspan="9"><?= e(t('admin.prestataires.none', $lang)) ?></td>
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
                                    <a class="admin-link" href="<?= route('admin_prestataires_show', ['id' => $p['id_prestataire']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.view', $lang)) ?></a>
                                    <a class="admin-link" href="<?= route('admin_prestataires_edit', ['id' => $p['id_prestataire']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.prestataires.edit', $lang)) ?></a>
                                    <form method="POST" action="<?= route('admin_prestataires_delete', ['id' => $p['id_prestataire']]) ?>?lang=<?= e($lang) ?>" onsubmit="return confirm('<?= e(t('admin.prestataires.delete_confirm', $lang)) ?>');">
                                        <button type="submit" class="admin-btn admin-btn-danger"><?= e(t('admin.prestataires.delete', $lang)) ?></button>
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