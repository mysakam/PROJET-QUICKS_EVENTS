<?php
$errors = $errors ?? [];
$item = $item ?? null;
?>
<section class="card">
    <h1><?= e($item ? 'Éditer client' : 'Créer client') ?></h1>

    <form method="post" action="<?= route($item ? 'users_update' : 'users_store', $item ? ['id' => $item['id_client']] : []) ?>" class="form-grid">
        <?= Csrf::field() ?>

        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" required value="<?= e($item['nom'] ?? '') ?>">
        <?php if (isset($errors['nom'])): ?><span class="error"><?= e($errors['nom']) ?></span><?php endif; ?>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" required value="<?= e($item['prenom'] ?? '') ?>">
        <?php if (isset($errors['prenom'])): ?><span class="error"><?= e($errors['prenom']) ?></span><?php endif; ?>

        <label for="email">Email</label>
        <input type="email" id="email" name="email" required value="<?= e($item['email'] ?? '') ?>">
        <?php if (isset($errors['email'])): ?><span class="error"><?= e($errors['email']) ?></span><?php endif; ?>

        <label for="telephone">Téléphone</label>
        <input type="text" id="telephone" name="telephone" value="<?= e($item['telephone'] ?? '') ?>">

        <div class="form-actions">
            <button type="submit"><?= e($item ? 'Mettre à jour' : 'Créer') ?></button>
            <a href="<?= route('users_index') ?>">Annuler</a>
        </div>
    </form>
</section>