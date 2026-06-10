<?php
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang ?? 'fr', ENT_QUOTES, 'UTF-8') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Devis', ENT_QUOTES, 'UTF-8') ?> | QUICK'EVENTS</title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
    <link rel="stylesheet" href="<?= asset('assets/css/devis.css') ?>">
    <?php if (!empty($pageCss)): ?>
        <link rel="stylesheet" href="<?= asset('assets/css/' . $pageCss) ?>">
    <?php endif; ?>
</head>

<body class="devis-layout">

    <div class="devis-page">
        <?php require __DIR__ . '/../partials/devis-header.php'; ?>

        <main class="devis-main">
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

        <?php require __DIR__ . '/../partials/devis-footer.php'; ?>
    </div>

    <script src="<?= asset('assets/js/main.js') ?>" defer></script>

</body>

</html>