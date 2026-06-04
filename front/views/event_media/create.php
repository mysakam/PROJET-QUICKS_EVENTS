<?php $themes = $themes ?? []; ?>

<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>A</span>jouter un media evenement</h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_event_medias_store') ?>">
            <div class="admin-form-row">
                <label for="theme_slug">Theme</label>
                <select name="theme_slug" id="theme_slug" required>
                    <?php foreach ($themes as $theme): ?>
                        <option value="<?= e($theme) ?>"><?= e($theme) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="admin-form-row">
                <label for="media_type">Type</label>
                <select name="media_type" id="media_type" required>
                    <option value="image">image</option>
                    <option value="video">video</option>
                </select>
            </div>

            <div class="admin-form-row">
                <label for="media_url">URL du media</label>
                <input type="text" name="media_url" id="media_url" required>
            </div>

            <div class="admin-form-row">
                <label for="title_fr">Titre FR</label>
                <input type="text" name="title_fr" id="title_fr" required>
            </div>

            <div class="admin-form-row">
                <label for="title_en">Titre EN</label>
                <input type="text" name="title_en" id="title_en" required>
            </div>

            <div class="admin-form-row">
                <label for="description_fr">Description FR</label>
                <textarea name="description_fr" id="description_fr" rows="4"></textarea>
            </div>

            <div class="admin-form-row">
                <label for="description_en">Description EN</label>
                <textarea name="description_en" id="description_en" rows="4"></textarea>
            </div>

            <div class="admin-form-row">
                <label for="position">Position</label>
                <input type="number" min="1" name="position" id="position" value="1" required>
            </div>

            <div class="admin-form-row admin-checkbox-row">
                <label>
                    <input type="checkbox" name="is_active" checked>
                    Actif
                </label>
            </div>

            <div class="admin-form-actions">
                <button class="admin-btn" type="submit">Enregistrer</button>
                <a class="btn" href="<?= route('admin_event_medias') ?>">Retour</a>
            </div>
        </form>
    </div>
</section>