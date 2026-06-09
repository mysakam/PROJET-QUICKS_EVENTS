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

    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="devis-page site-main">
        <?php require __DIR__ . '/../partials/devis-header.php'; ?>

        <main class="devis-main">
            <?php require $viewPath; ?>
        </main>

        <?php require __DIR__ . '/../partials/devis-footer.php'; ?>
    </div>

</body>

</html>