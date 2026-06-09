<?php
$lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
?>
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">QUICK'EVENTS</div>

        <div class="footer-links">
            <a href="<?= route('home') . '?lang=' . $lang ?>#apropos">
                <?= $lang === 'fr' ? 'A propos' : 'About' ?>
            </a>
            <a href="<?= route('catalogues') . '?lang=' . $lang ?>">
                <?= $lang === 'fr' ? 'Catalogues' : 'Catalogues' ?>
            </a>
            <a href="<?= route('login') . '?lang=' . $lang ?>">
                <?= $lang === 'fr' ? 'Connexion' : 'Login' ?>
            </a>
            <a href="<?= route('register') . '?lang=' . $lang ?>">
                <?= $lang === 'fr' ? 'Inscription' : 'Register' ?>
            </a>
        </div>

        <p class="footer-copy">
            <?= $lang === 'fr' ? 'Tous droits réservés' : 'All rights reserved' ?>
        </p>
    </div>
</footer>