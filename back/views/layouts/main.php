<?php
$viewPath = $viewPath ?? null;
$flashError = Session::flash('error');
$title = $pageTitle ?? 'Back Office';
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/admin.css') ?>">
</head>

<body>
    <header class="topbar">
        <strong>QuickEvents Back</strong>
        <?php if (Auth::check()): ?>
            <nav>
                <a href="<?= route('dashboard') ?>">Dashboard</a>
                <a href="<?= route('users_index') ?>">Clients</a>
                <a href="<?= route('categories_index') ?>">Catégories</a>
                <a href="<?= route('prestations_index') ?>">Prestations</a>
                <a href="<?= route('devis_index') ?>">Devis</a>
                <a href="<?= route('media_index') ?>">Médias</a>
                <a href="<?= route('logout') ?>">Déconnexion</a>
            </nav>
        <?php endif; ?>
    </header>

    <main class="container">
        <?php if (!empty($flashError)): ?>
            <div class="alert error"><?= e($flashError) ?></div>
        <?php endif; ?>

        <?php if (is_string($viewPath) && file_exists($viewPath)) require $viewPath; ?>
    </main>

    <script src="<?= asset('assets/js/admin.js') ?>" defer></script>
</body>

</html>