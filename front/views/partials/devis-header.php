<?php $lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr')); ?>
<header class="devis-header">
    <div class="devis-header-inner">
        <div class="brand">
            <img src="<?= asset('assets/images/logo-qe.png') ?>" alt="Quick'Events">
            <div class="brand-name">QUICK'EVENTS</div>
        </div>

        <h1 class="devis-title"><?= e($pageTitle ?? 'DEVIS PROPOSE') ?></h1>

        <div class="back-btn">
            <a href="<?= e($backUrl ?? route('account')) . '?lang=' . $lang ?>">RETOUR &rsaquo;</a>
        </div>
    </div>
</header>