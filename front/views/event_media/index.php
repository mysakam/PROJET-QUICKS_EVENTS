<?php
$themes = $themes ?? [];
$themeOptions = $themeOptions ?? [];
$themeFilter = $themeFilter ?? null;
$searchQuery = $searchQuery ?? '';
$activeFilter = $activeFilter ?? '';
$medias = $medias ?? [];
?>

<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><span>A</span>dmin médias événement</h2>

        <?php if (!empty($_SESSION['success'])): ?>
            <p class="admin-alert admin-alert-success"><?= e($_SESSION['success']) ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="admin-media-actions">
            <a class="btn" href="<?= route('admin_event_medias_create') ?>">Ajouter un média</a>
            <a class="btn" href="<?= route('admin_event_medias') ?>">Tout afficher</a>
        </div>

        <form class="admin-filter-form" method="GET" action="<?= route('admin_event_medias') ?>">
            <label for="theme">Filtrer par thème</label>
            <select name="theme" id="theme">
                <option value="">-- Tous --</option>
                <?php foreach ($themes as $theme): ?>
                    <option value="<?= e($theme) ?>" <?= $themeFilter === $theme ? 'selected' : '' ?>><?= e($themeOptions[$theme] ?? $theme) ?></option>
                <?php endforeach; ?>
            </select>
            <label for="active">Actif</label>
            <select name="active" id="active">
                <option value="" <?= $activeFilter === '' ? 'selected' : '' ?>>-- Tous --</option>
                <option value="1" <?= $activeFilter === '1' ? 'selected' : '' ?>>Oui</option>
                <option value="0" <?= $activeFilter === '0' ? 'selected' : '' ?>>Non</option>
            </select>
            <label for="q">Recherche</label>
            <input id="q" name="q" type="text" value="<?= e($searchQuery) ?>" placeholder="Titre, URL, type">
            <button class="admin-btn" type="submit">Filtrer</button>
            <a class="btn" href="<?= route('admin_event_medias') ?>">Réinitialiser</a>
        </form>

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thème</th>
                        <th>Type</th>
                        <th>Titre FR</th>
                        <th>Position</th>
                        <th>Actif</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($medias)): ?>
                        <tr>
                            <td colspan="7">Aucun média trouvé.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($medias as $media): ?>
                            <tr>
                                <td><?= (int)$media['id_media'] ?></td>
                                <td><?= e($themeOptions[$media['theme_slug']] ?? $media['theme_slug']) ?></td>
                                <td><?= e($media['media_type']) ?></td>
                                <td><?= e($media['title_fr']) ?></td>
                                <td><?= (int)$media['position'] ?></td>
                                <td><?= !empty($media['is_active']) ? 'Oui' : 'Non' ?></td>
                                <td class="admin-table-actions">
                                    <a class="admin-link" href="<?= route('admin_event_medias_edit', ['id' => $media['id_media']]) ?>">Modifier</a>
                                    <form method="POST" action="<?= route('admin_event_medias_delete', ['id' => $media['id_media']]) ?>" onsubmit="return confirm('Supprimer ce média ?');">
                                        <button class="admin-btn admin-btn-danger" type="submit">Supprimer</button>
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