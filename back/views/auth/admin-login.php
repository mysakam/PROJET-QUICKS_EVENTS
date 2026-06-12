<?php
$error = Session::flash('error');
$errors = Session::flash('errors') ?? [];
?>
<section class="card">
    <h1>Connexion - Administrateur</h1>
    <?php if ($error): ?>
        <div class="alert error"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" action="<?= route('admin_login_post') ?>" class="form-grid" data-fetch-form>
        <?= Csrf::field() ?>

        <label for="email">Email administrateur</label>
        <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
        <?php if (isset($errors['email'])): ?>
            <span class="error"><?= e($errors['email']) ?></span>
        <?php endif; ?>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
        <?php if (isset($errors['password'])): ?>
            <span class="error"><?= e($errors['password']) ?></span>
        <?php endif; ?>

        <button type="submit">Se connecter</button>
    </form>
</section>