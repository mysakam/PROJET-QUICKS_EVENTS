<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$langQuery = '?lang=' . $lang;
$toggleLang = $lang === 'fr' ? 'en' : 'fr';
$homeUrl = route('home') . $langQuery;
$catalogueUrl = route('catalogues') . $langQuery;
$isHome = (parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) === route('home'));
$eventsLink = $isHome ? '#evenements' : ($homeUrl . '#evenements');
?>

<header>
    <a href="<?= $homeUrl ?>" class="logo"><span> QUICK'EVENTS </span></a>
    <button class="menu-toggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="main-nav">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <ul class="navbar" id="main-nav">
        <li><a href="<?= $homeUrl ?>" class="btn"><?= $lang === 'fr' ? 'ACCUEIL' : 'HOME' ?></a></li>
        <li><a href="<?= $eventsLink ?>" class="btn"><?= $lang === 'fr' ? 'EVENEMENTS' : 'EVENTS' ?></a></li>
        <li><a href="<?= $catalogueUrl ?>" class="btn"><?= $lang === 'fr' ? 'CATALOGUES' : 'CATALOGUES' ?></a></li>

        <?php if (!empty($_SESSION['client'])): ?>
            <li><a href="<?= route('panier') ?>" class="btn"><?= $lang === 'fr' ? 'PANIER' : 'CART' ?></a></li>
            <li><a href="<?= route('devis_index') ?>" class="btn"><?= $lang === 'fr' ? 'MES DEVIS' : 'MY QUOTES' ?></a></li>
            <li><a href="<?= route('account') ?>" class="btn"><?= $lang === 'fr' ? 'MON COMPTE' : 'MY ACCOUNT' ?></a></li>
            <?php if (!empty($_SESSION['client']['is_admin'])): ?>
                <li><a href="<?= route('admin_event_medias') ?>" class="btn"><?= $lang === 'fr' ? 'ADMIN MEDIAS' : 'MEDIA ADMIN' ?></a></li>
            <?php endif; ?>
            <li><a href="<?= route('logout') ?>" class="btn"><?= $lang === 'fr' ? 'DECONNEXION' : 'LOG OUT' ?></a></li>
        <?php else: ?>
            <li><a href="<?= route('login') . $langQuery ?>" class="btn"><?= $lang === 'fr' ? 'CONNEXION' : 'LOGIN' ?></a></li>
            <li><a href="<?= route('register') . $langQuery ?>" class="btn"><?= $lang === 'fr' ? 'INSCRIPTION' : 'REGISTER' ?></a></li>
        <?php endif; ?>
        <li><a href="<?= route('home') . '?lang=' . $toggleLang ?>" class="btn-transcription"><?= $lang === 'fr' ? 'FR/EN' : 'EN/FR' ?></a></li>
    </ul>
</header>