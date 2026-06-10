<?php
// Forcer l'encodage UTF-8 côté navigateur
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}

$normalizedViewPath = isset($viewPath) ? str_replace('\\', '/', $viewPath) : '';
$isDevisView = $normalizedViewPath !== '' && strpos($normalizedViewPath, '/views/devis/') !== false;
?>
<!DOCTYPE html>
<html lang="<?= e($lang ?? 'fr') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? "QUICK'EVENTS") ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>

<body class="<?= $isDevisView ? 'devis-layout' : '' ?>">

    <?php
    $headerPath = __DIR__ . '/../partials/header.php';
    if (file_exists($headerPath)) {
        require $headerPath;
    }
    ?>

    <main class="site-main">
        <?php if (isset($viewPath) && file_exists($viewPath)) {
            require $viewPath;
        } ?>
    </main>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="flash-toast flash-toast-success" role="status" aria-live="polite" data-flash-toast>
            <?= e($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php
    $footerPath = __DIR__ . '/../partials/footer.php';
    if (file_exists($footerPath)) {
        require $footerPath;
    }
    ?>

    <script src="<?= asset('assets/js/main.js') ?>" defer></script>

</body>

</html>