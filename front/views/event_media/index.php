<?php
$themes = $themes ?? [];
$themeFilter = $themeFilter ?? null;
$medias = $medias ?? [];
?>

<section class="apropos">
    <h2 class="titre-texte"><span>A</span>dmin medias evenement</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <p style="color: #0a7f3f;"><?= e($_SESSION['success']) ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color: #b42318;"><?= e($_SESSION['error']) ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <p>
        <a class="btn" href="<?= route('admin_event_medias_create') ?>">Ajouter un media</a>
        <a class="btn" href="<?= route('admin_event_medias') ?>">Tout afficher</a>
    </p>

    <form method="GET" action="<?= route('admin_event_medias') ?>" style="margin: 20px 0;">
        <label for="theme">Filtrer par theme</label>
        <select name="theme" id="theme">
            <option value="">-- Tous --</option>
            <?php foreach ($themes as $theme): ?>
                <option value="<?= e($theme) ?>" <?= $themeFilter === $theme ? 'selected' : '' ?>><?= e($theme) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filtrer</button>
    </form>

    <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; background: #fff;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Theme</th>
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
                    <td colspan="7">Aucun media trouve.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($medias as $media): ?>
                    <tr>
                        <td><?= (int)$media['id_media'] ?></td>
                        <td><?= e($media['theme_slug']) ?></td>
                        <td><?= e($media['media_type']) ?></td>
                        <td><?= e($media['title_fr']) ?></td>
                        <td><?= (int)$media['position'] ?></td>
                        <td><?= !empty($media['is_active']) ? 'Oui' : 'Non' ?></td>
                        <td>
                            <a href="<?= route('admin_event_medias_edit', ['id' => $media['id_media']]) ?>">Modifier</a>
                            <form method="POST" action="<?= route('admin_event_medias_delete', ['id' => $media['id_media']]) ?>" style="display:inline; margin-left: 10px;" onsubmit="return confirm('Supprimer ce media ?');">
                                <button type="submit">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</section>