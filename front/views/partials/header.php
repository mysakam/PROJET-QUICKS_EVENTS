<?php
$lang = ($_GET['lang'] ?? ($lang ?? 'fr')) === 'en' ? 'en' : 'fr';
$langQuery = '?lang=' . $lang;
$toggleLang = $lang === 'fr' ? 'en' : 'fr';
?>
<header>
    <a href="<?= route('home') . $langQuery ?>" class="logo"><span>QUICK'EVENTS</span></a>

    <ul class="navbar">
        <li><a href="<?= route('home') . $langQuery ?>#apropos"
                class="btn"><?= $lang === 'fr' ? 'A PROPOS' : 'ABOUT' ?></a></li>
        <li><a href="<?= route('home') . $langQuery ?>#evenements"
                class="btn"><?= $lang === 'fr' ? 'EVENEMENTS' : 'EVENTS' ?></a></li>
        <li><a href="<?= route('catalogues') . $langQuery ?>"
                class="btn"><?= $lang === 'fr' ? 'CATALOGUES' : 'CATALOGUES' ?></a></li>
        <li><a href="<?= route('login') . $langQuery ?>" class="btn"><?= $lang === 'fr' ? 'CONNEXION' : 'LOGIN' ?></a>
        </li>
        <li><a href="<?= route('register') . $langQuery ?>"
                class="btn"><?= $lang === 'fr' ? 'INSCRIPTION' : 'REGISTER' ?></a></li>
        <li><a href="<?= route('home') . '?lang=' . $toggleLang ?>"
                class="btn-transcription"><?= $lang === 'fr' ? 'FR/EN' : 'EN/FR' ?></a></li>
    </ul>
</header>