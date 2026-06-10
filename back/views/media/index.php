<?php
$medias = $medias ?? [];
$notice = $notice ?? null;
?>
<section class="card">
    <h1>Medias</h1>
    <?php if (!empty($notice)): ?>
        <p><?= e($notice) ?></p>
    <?php endif; ?>

    <p><a href="<?= route('media_create') ?>">Ajouter un media</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Theme</th>
                <th>Type</th>
                <th>Titre</th>
                <th>Actif</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medias as $m): ?>
                <tr>
                    <td><?= (int) $m['id_media'] ?></td>
                    <td><?= e($m['theme_slug'] ?? '-') ?></td>
                    <td><?= e($m['type_media'] ?? '-') ?></td>
                    <td><?= e($m['titre'] ?? '-') ?></td>
                    <td><?= (int) ($m['is_active'] ?? 0) ? 'Oui' : 'Non' ?></td>
                    <td><a href="<?= route('media_edit', ['id' => (int) $m['id_media']]) ?>">Editer</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>