<?php
$errors = $errors ?? [];
$item = $item ?? null;
?>
<section class="card">
    <h1><?= e($item ? 'Éditer catégorie' : 'Créer catégorie') ?></h1>

    <form method="post" action="<?= route($item ? 'categories_update' : 'categories_store', $item ? ['id' => $item['id_categorie']] : []) ?>" class="form-grid">
        <?= Csrf::field() ?>

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required value="<?= e($item['nom'] ?? '') ?>">
        <?php if (isset($errors['nom'])): ?><span class="error"><?= e($errors['nom']) ?></span><?php endif; ?>

        <label for="slug">Slug</label>
        <input type="text" id="slug" name="slug" required value="<?= e($item['slug'] ?? '') ?>">
        <?php if (isset($errors['slug'])): ?><span class="error"><?= e($errors['slug']) ?></span><?php endif; ?>

        <div class="form-actions">
            <button type="submit"><?= e($item ? 'Mettre à jour' : 'Créer') ?></button>
            <a href="<?= route('categories_index') ?>">Annuler</a>
        </div>
    </form>
</section>