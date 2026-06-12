<?php
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}
?>
<!DOCTYPE html>
<html lang="<?= e($lang ?? 'fr') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= e(Csrf::token()) ?>">
    <title><?= e($pageTitle ?? "QUICK'EVENTS") ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>

<body class="auth-page">

    <?php
    $headerPath = __DIR__ . '/../partials/header.php';
    if (file_exists($headerPath)) {
        require $headerPath;
    }
    ?>

    <main class="auth-main">
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

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="flash-toast flash-toast-error" role="alert" aria-live="assertive" data-flash-toast>
            <?= e($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
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