<?php $themes = $themes ?? []; ?>

<section class="apropos">
    <h2 class="titre-texte"><span>A</span>jouter un media evenement</h2>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color: #b42318;"><?= e($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= route('admin_event_medias_store') ?>">
        <p>
            <label for="theme_slug">Theme</label><br>
            <select name="theme_slug" id="theme_slug" required>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?= e($theme) ?>"><?= e($theme) ?></option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <label for="media_type">Type</label><br>
            <select name="media_type" id="media_type" required>
                <option value="image">image</option>
                <option value="video">video</option>
            </select>
        </p>

        <p>
            <label for="media_url">URL du media</label><br>
            <input type="text" name="media_url" id="media_url" required style="width: 100%;">
        </p>

        <p>
            <label for="title_fr">Titre FR</label><br>
            <input type="text" name="title_fr" id="title_fr" required style="width: 100%;">
        </p>

        <p>
            <label for="title_en">Titre EN</label><br>
            <input type="text" name="title_en" id="title_en" required style="width: 100%;">
        </p>

        <p>
            <label for="description_fr">Description FR</label><br>
            <textarea name="description_fr" id="description_fr" rows="4" style="width: 100%;"></textarea>
        </p>

        <p>
            <label for="description_en">Description EN</label><br>
            <textarea name="description_en" id="description_en" rows="4" style="width: 100%;"></textarea>
        </p>

        <p>
            <label for="position">Position</label><br>
            <input type="number" min="1" name="position" id="position" value="1" required>
        </p>

        <p>
            <label>
                <input type="checkbox" name="is_active" checked>
                Actif
            </label>
        </p>

        <p>
            <button type="submit">Enregistrer</button>
            <a href="<?= route('admin_event_medias') ?>">Retour</a>
        </p>
    </form>
</section>