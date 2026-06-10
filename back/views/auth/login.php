<section class="card">
    <h1>Connexion back-office</h1>
    <p>Connectez-vous avec un compte client admin autorise.</p>

    <form method="post" action="<?= route('login_post') ?>" class="form-grid">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Se connecter</button>
    </form>
</section>