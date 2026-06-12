<header class="devis-header">
    <div class="devis-header-inner">
        <div class="brand">
            <img src="/assets/images/logo.png" alt="Quick'Events">
            <div class="brand-name">QUICK'EVENTS</div>
        </div>

        <h1 class="devis-title"><?= e($pageTitle ?? 'DEVIS') ?></h1>

        <div class="back-btn">
            <a href="<?= e($backUrl ?? route('account')) ?>">RETOUR &rsaquo;</a>
        </div>
    </div>
</header>