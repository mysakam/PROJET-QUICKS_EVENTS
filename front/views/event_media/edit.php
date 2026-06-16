<?php
$themes = $themes ?? [];
$themeOptions = $themeOptions ?? [];
$media = $media ?? [];
$lang = $lang ?? current_lang();
?>

<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><?= e(t('admin.media.edit_title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_event_medias_update', ['id' => $media['id_media']]) ?>?lang=<?= e($lang) ?>">
            <div class="admin-form-row">
                <label for="theme_slug"><?= e(t('admin.media.theme', $lang)) ?></label>
                <select name="theme_slug" id="theme_slug" required>
                    <?php foreach ($themes as $theme): ?>
                        <option value="<?= e($theme) ?>" <?= ($media['theme_slug'] === $theme) ? 'selected' : '' ?>><?= e($themeOptions[$theme] ?? $theme) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="admin-form-row">
                <label for="media_type"><?= e(t('admin.media.type', $lang)) ?></label>
                <select name="media_type" id="media_type" required>
                    <option value="image" <?= $media['media_type'] === 'image' ? 'selected' : '' ?>>image</option>
                    <option value="video" <?= $media['media_type'] === 'video' ? 'selected' : '' ?>>video</option>
                </select>
            </div>

            <div class="admin-form-row">
                <label for="media_url"><?= e(t('admin.media.media_url', $lang)) ?></label>
                <input type="text" name="media_url" id="media_url" value="<?= e($media['media_url']) ?>" required>
            </div>

            <div class="admin-form-row">
                <label for="title_fr"><?= e(t('admin.media.title_fr', $lang)) ?></label>
                <input type="text" name="title_fr" id="title_fr" value="<?= e($media['title_fr']) ?>" required>
            </div>

            <div class="admin-form-row">
                <label for="title_en"><?= e(t('admin.media.title_en', $lang)) ?></label>
                <input type="text" name="title_en" id="title_en" value="<?= e($media['title_en']) ?>" required>
            </div>

            <div class="admin-form-row">
                <label for="description_fr"><?= e(t('admin.media.desc_fr', $lang)) ?></label>
                <textarea name="description_fr" id="description_fr" rows="4"><?= e($media['description_fr'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-row">
                <label for="description_en"><?= e(t('admin.media.desc_en', $lang)) ?></label>
                <textarea name="description_en" id="description_en" rows="4"><?= e($media['description_en'] ?? '') ?></textarea>
            </div>

            <div class="admin-form-row">
                <label for="position"><?= e(t('admin.media.position', $lang)) ?></label>
                <input type="number" min="1" name="position" id="position" value="<?= (int)$media['position'] ?>" required>
            </div>

            <div class="admin-form-row admin-checkbox-row">
                <label>
                    <input type="checkbox" name="is_active" <?= !empty($media['is_active']) ? 'checked' : '' ?>>
                    <?= e(t('admin.media.active', $lang)) ?>
                </label>
            </div>

            <div class="admin-form-actions">
                <button class="admin-btn" type="submit"><?= e(t('admin.media.update', $lang)) ?></button>
                <a class="btn" href="<?= route('admin_event_medias') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.back', $lang)) ?></a>
            </div>
        </form>
    </div>
</section>