<h1>Connexion</h1>

<?php if (!empty($_SESSION['error'])): ?>
    <div style="color: red;"> <?= htmlspecialchars($_SESSION['error']) ?> </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<form action="<?= route('login_post') ?>" method="post">
    <label for="email">Email :</label><br>
    <input type="email" name="email" id="email" required><br><br>

    <label for="password">Mot de passe :</label><br>
    <input type="password" name="password" id="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>