<?php if (!headers_sent()) header('Content-Type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html lang="<?= e($lang ?? 'fr') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? "QUICK'EVENTS") ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>
    <main><?php require $viewPath; ?></main>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
</body>

</html>