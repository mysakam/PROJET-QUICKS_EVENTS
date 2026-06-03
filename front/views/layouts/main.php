<?php
// Forcer l'encodage UTF-8 côté navigateur
if (!headers_sent()) {
    header('Content-Type: text/html; charset=utf-8');
}
?>
<!DOCTYPE html>
<html lang="<?= e($lang ?? 'fr') ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? "QUICK'EVENTS") ?></title>
    <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>">
</head>

<body>

    <?php
    $headerPath = __DIR__ . '/../partials/header.php';
    if (file_exists($headerPath)) {
        require $headerPath;
    }
    ?>

    <main>
        <?php if (isset($viewPath) && file_exists($viewPath)) {
            require $viewPath;
        } ?>
    </main>

    <?php
    $footerPath = __DIR__ . '/../partials/footer.php';
    if (file_exists($footerPath)) {
        require $footerPath;
    }
    ?>

</body>

</html>