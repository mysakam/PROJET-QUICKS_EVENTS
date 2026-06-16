<?php
$themes = $themes ?? [];
$themeOptions = $themeOptions ?? [];
$themeFilter = $themeFilter ?? null;
$searchQuery = $searchQuery ?? '';
$activeFilter = $activeFilter ?? '';
$medias = $medias ?? [];
$lang = $lang ?? current_lang();
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><?= e(t('admin.media.title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="admin-alert admin-alert-success"><?= e($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_event_medias_create') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.media.add', $lang)) ?></a>
            <a class="btn" href="<?= route('admin_event_medias') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.media.all', $lang)) ?></a>
        </div>

        <form class="admin-filter-form" method="GET" action="<?= route('admin_event_medias') ?>">
            <input type="hidden" name="lang" value="<?= e($lang) ?>">
            <label for="theme"><?= e(t('admin.media.theme_filter', $lang)) ?></label>
            <select name="theme" id="theme">
                <option value=""><?= e(t('admin.prestataires.all', $lang)) ?></option>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?= e($theme) ?>" <?= $themeFilter === $theme ? 'selected' : '' ?>><?= e($themeOptions[$theme] ?? $theme) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="active"><?= e(t('admin.media.active', $lang)) ?></label>
            <select name="active" id="active">
                <option value="" <?= $activeFilter === '' ? 'selected' : '' ?>><?= e(t('admin.prestataires.all', $lang)) ?></option>
                <option value="1" <?= $activeFilter === '1' ? 'selected' : '' ?>><?= e(t('admin.media.yes', $lang)) ?></option>
                <option value="0" <?= $activeFilter === '0' ? 'selected' : '' ?>><?= e(t('admin.media.no', $lang)) ?></option>
            </select>
            <label for="q"><?= e(t('admin.media.search', $lang)) ?></label>
            <input id="q" name="q" type="text" value="<?= e($searchQuery) ?>" placeholder="<?= e(t('admin.media.search_ph', $lang)) ?>">
            <button class="admin-btn" type="submit"><?= e(t('admin.media.filter', $lang)) ?></button>
            <a class="btn" href="<?= route('admin_event_medias') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.media.reset', $lang)) ?></a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th><?= e(t('admin.media.th_theme', $lang)) ?></th>
                        <th><?= e(t('admin.media.th_type', $lang)) ?></th>
                        <th><?= e(t('admin.media.th_title_fr', $lang)) ?></th>
                        <th><?= e(t('admin.media.th_position', $lang)) ?></th>
                        <th><?= e(t('admin.media.th_active', $lang)) ?></th>
                        <th><?= e(t('admin.media.th_actions', $lang)) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($medias)): ?>
                        <tr>
                            <td colspan="7"><?= e(t('admin.media.none', $lang)) ?></td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($medias as $media): ?>
                            <tr>
                                <td><?= (int)$media['id_media'] ?></td>
                                <td><?= e($themeOptions[$media['theme_slug']] ?? $media['theme_slug']) ?></td>
                                <td><?= e($media['media_type']) ?></td>
                                <td><?= e($media['title_fr']) ?></td>
                                <td><?= (int)$media['position'] ?></td>
                                <td><?= !empty($media['is_active']) ? e(t('admin.media.yes', $lang)) : e(t('admin.media.no', $lang)) ?></td>
                                <td class="admin-table-actions">
                                    <a class="admin-link" href="<?= route('admin_event_medias_edit', ['id' => $media['id_media']]) ?>?lang=<?= e($lang) ?>"><?= e(t('admin.media.edit', $lang)) ?></a>
                                    <form method="POST" action="<?= route('admin_event_medias_delete', ['id' => $media['id_media']]) ?>?lang=<?= e($lang) ?>" onsubmit="return confirm('<?= e(t('admin.media.delete_confirm', $lang)) ?>');">
                                        <button class="admin-btn admin-btn-danger" type="submit"><?= e(t('admin.media.delete', $lang)) ?></button>
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