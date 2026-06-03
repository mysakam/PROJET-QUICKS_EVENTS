<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$langQuery = '?lang=' . $lang;
$toggleLang = $lang === 'fr' ? 'en' : 'fr';
$homeUrl = route('home') . $langQuery;
$catalogueUrl = route('catalogues') . $langQuery;
$isHome = (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) === route('home'));
$aboutLink = $isHome ? '#apropos' : ($homeUrl . '#apropos');
$eventsLink = $isHome ? '#evenements' : ($homeUrl . '#evenements');
?>

<header>
    <a href="<?= $homeUrl ?>" class="logo"><span> QUICK'EVENTS </span></a>
    <ul class="navbar">
        <li><a href="<?= $aboutLink ?>" class="btn"><?= $lang === 'fr' ? 'A PROPOS' : 'ABOUT' ?></a></li>
        <li><a href="<?= $eventsLink ?>" class="btn"><?= $lang === 'fr' ? 'EVENEMENTS' : 'EVENTS' ?></a></li>
        <li><a href="<?= $catalogueUrl ?>" class="btn"><?= $lang === 'fr' ? 'CATALOGUES' : 'CATALOGUES' ?></a></li>

        <?php if (!empty($_SESSION['client'])): ?>
            <li><a href="<?= route('panier') ?>" class="btn"><?= $lang === 'fr' ? 'PANIER' : 'CART' ?></a></li>
            <li><a href="<?= route('devis_index') ?>" class="btn"><?= $lang === 'fr' ? 'MES DEVIS' : 'MY QUOTES' ?></a></li>
            <li><a href="<?= route('account') ?>" class="btn"><?= $lang === 'fr' ? 'MON COMPTE' : 'MY ACCOUNT' ?></a></li>
            <li><a href="<?= route('logout') ?>" class="btn"><?= $lang === 'fr' ? 'DECONNEXION' : 'LOG OUT' ?></a></li>
        <?php else: ?>
            <li><a href="<?= route('login') . $langQuery ?>" class="btn"><?= $lang === 'fr' ? 'CONNEXION' : 'LOGIN' ?></a></li>
            <li><a href="<?= route('register') . $langQuery ?>" class="btn"><?= $lang === 'fr' ? 'INSCRIPTION' : 'REGISTER' ?></a></li>
        <?php endif; ?>

        <a href="<?= route('home') . '?lang=' . $toggleLang ?>" class="btn-transcription"><?= $lang === 'fr' ? 'FR/EN' : 'EN/FR' ?></a>
    </ul>
</header>