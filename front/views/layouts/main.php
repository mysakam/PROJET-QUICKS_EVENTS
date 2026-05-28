<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QUICK'EVENTS</title>
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
        <?= $content ?? '' ?>
    </main>

</body>

</html>