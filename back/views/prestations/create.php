<?php
$errors = $errors ?? [];
$item = $item ?? null;
$categories = $categories ?? [];
?>
<section class="card">
    <h1><?= e($item ? 'Éditer prestation' : 'Créer prestation') ?></h1>

    <form method="post" action="<?= route($item ? 'prestations_update' : 'prestations_store', $item ? ['id' => $item['id_prestation']] : []) ?>" class="form-grid">
        <?= Csrf::field() ?>

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required value="<?= e($item['nom'] ?? '') ?>">
        <?php if (isset($errors['nom'])): ?><span class="error"><?= e($errors['nom']) ?></span><?php endif; ?>

        <label for="id_categorie">Catégorie</label>
        <select id="id_categorie" name="id_categorie" required>
            <option value="">-- Sélectionner --</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= (int) $c['id_categorie'] ?>" <?= ($item['id_categorie'] ?? 0) == $c['id_categorie'] ? 'selected' : '' ?>>
                    <?= e($c['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="prix_unitaire">Prix unitaire (EUR)</label>
        <input type="number" id="prix_unitaire" name="prix_unitaire" step="0.01" required value="<?= e((string) ($item['prix_unitaire'] ?? '')) ?>">
        <?php if (isset($errors['prix_unitaire'])): ?><span class="error"><?= e($errors['prix_unitaire']) ?></span><?php endif; ?>

        <div class="form-actions">
            <button type="submit"><?= e($item ? 'Mettre à jour' : 'Créer') ?></button>
            <a href="<?= route('prestations_index') ?>">Annuler</a>
        </div>
    </form>
</section>