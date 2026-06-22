<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$langQuery = '?lang=' . $lang;

$txt = [
    'fr' => [
        'title' => 'Inscription',
        'intro' => 'Créez votre compte client pour accéder à votre espace personnel et préparer ensuite votre fiche événement.',
        'highlights' => ['Compte créé en quelques étapes', 'Accès direct aux catalogues', 'Fiche événement après inscription'],
        'login' => 'J’ai déjà un compte',
        'card' => 'Créer mon compte',
        'nom' => 'Nom',
        'prenom' => 'Prénom',
        'email' => 'Email',
        'telephone' => 'Téléphone',
        'password' => 'Mot de passe',
        'password_confirm' => 'Confirmer le mot de passe',
        'submit' => 'Créer mon compte',
    ],
    'en' => [
        'title' => 'Register',
        'intro' => 'Create your client account to access your personal area and then prepare your event form.',
        'highlights' => ['Account created in a few steps', 'Direct access to catalogues', 'Event form after signup'],
        'login' => 'I already have an account',
        'card' => 'Create my account',
        'nom' => 'Last name',
        'prenom' => 'First name',
        'email' => 'Email',
        'telephone' => 'Phone',
        'password' => 'Password',
        'password_confirm' => 'Confirm password',
        'submit' => 'Create my account',
    ],
];
$t = $txt[$lang];
?>

<section class="apropos auth-section">
    <div class="admin-media-shell auth-shell">
        <div class="auth-copy">
            <p class="auth-kicker">QUICK'EVENTS</p>
            <h2 class="titre-texte"><?= e($t['title']) ?></h2>
            <p><?= e($t['intro']) ?></p>

            <div class="auth-highlights">
                <?php foreach ($t['highlights'] as $highlight): ?>
                    <div class="auth-highlight"><?= e($highlight) ?></div>
                <?php endforeach; ?>
            </div>

            <a class="btn auth-side-link" href="<?= route('login') . $langQuery ?>"><?= e($t['login']) ?></a>
        </div>

        <div class="admin-media-shell admin-form-shell auth-card">
            <h3 class="auth-card-title"><?= e($t['card']) ?></h3>

            <?php if (!empty($_SESSION['error'])): ?>
                <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="admin-form" method="POST" action="<?= route('register_post') . $langQuery ?>" data-fetch-form>
                <div class="auth-form-grid">
                    <div class="admin-form-row">
                        <label for="nom"><?= e($t['nom']) ?></label>
                        <input type="text" id="nom" name="nom" required value="<?= e($oldRegister['nom'] ?? '') ?>">
                    </div>
                    <div class="admin-form-row">
                        <label for="prenom"><?= e($t['prenom']) ?></label>
                        <input type="text" id="prenom" name="prenom" required value="<?= e($oldRegister['prenom'] ?? '') ?>">
                    </div>
                </div>

                <div class="admin-form-row">
                    <label for="email"><?= e($t['email']) ?></label>
                    <input type="email" id="email" name="email" required value="<?= e($oldRegister['email'] ?? '') ?>">
                </div>
                <div class="admin-form-row">
                    <label for="telephone"><?= e($t['telephone']) ?></label>
                    <input type="text" id="telephone" name="telephone" value="<?= e($oldRegister['telephone'] ?? '') ?>">
                </div>
                <div class="admin-form-row">
                    <label for="password"><?= e($t['password']) ?></label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="admin-form-row">
                    <label for="password_confirm"><?= e($t['password_confirm']) ?></label>
                    <input type="password" id="password_confirm" name="password_confirm" required>
                </div>

                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit"><?= e($t['submit']) ?></button>
                </div>
            </form>
        </div>
    </div>
</section>