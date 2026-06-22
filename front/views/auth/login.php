<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$langQuery = '?lang=' . $lang;

$txt = [
    'fr' => [
        'title' => 'Connexion',
        'intro' => 'Accédez à votre espace client pour suivre vos devis, votre panier et votre compte.',
        'highlights' => ['Catalogue et devis centralisés', 'Historique de votre compte', 'Accès rapide à vos actions'],
        'register' => 'Créer un compte',
        'card' => 'Se connecter',
        'email' => 'Email',
        'password' => 'Mot de passe',
        'submit' => 'Se connecter',
    ],
    'en' => [
        'title' => 'Login',
        'intro' => 'Access your client area to track your quotes, cart, and account.',
        'highlights' => ['Centralized catalogue and quotes', 'Account history', 'Quick access to your actions'],
        'register' => 'Create an account',
        'card' => 'Login',
        'email' => 'Email',
        'password' => 'Password',
        'submit' => 'Login',
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

            <a class="btn auth-side-link" href="<?= route('register') . $langQuery ?>"><?= e($t['register']) ?></a>
        </div>

        <div class="admin-media-shell admin-form-shell auth-card">
            <h3 class="auth-card-title"><?= e($t['card']) ?></h3>

            <?php if (!empty($_SESSION['error'])): ?>
                <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
                <p class="admin-alert admin-alert-info" style="margin-top:6px;font-size:.88rem;">
                    <?= e(t('auth.forgot_pw', $lang)) ?>
                    <a href="mailto:akamsamy69@gmail.com">akamsamy69@gmail.com</a>
                </p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form class="admin-form" action="<?= route('login_post') . $langQuery ?>" method="post" data-fetch-form>
                <div class="admin-form-row">
                    <label for="email"><?= e($t['email']) ?></label>
                    <input type="email" name="email" id="email" required value="<?= e($oldLoginEmail ?? '') ?>">
                </div>

                <div class="admin-form-row">
                    <label for="password"><?= e($t['password']) ?></label>
                    <input type="password" name="password" id="password" required>
                    <p style="margin-top:8px;font-size:.88rem;">
                        <?= e(t('auth.forgot_pw', $lang)) ?>
                        <a href="mailto:akamsamy69@gmail.com">akamsamy69@gmail.com</a>
                    </p>
                </div>

                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit"><?= e($t['submit']) ?></button>
                </div>
            </form>
        </div>
    </div>
</section>