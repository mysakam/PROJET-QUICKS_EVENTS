<header class="site-header">
    <div class="header-left">
        <a href="<?= route('home') ?>" class="brand">
            <img src="<?= asset('images/logo.png') ?>" alt="QUICK'EVENTS" class="brand-logo">
            <span>QUICK'EVENTS</span>
        </a>
    </div>
    <div class="header-actions">
        <a href="<?= route('login') ?>" class="pill-btn"><span class="icon">👥</span> CONNEXION</a>
        <a href="<?= route('register') ?>" class="pill-btn"><span class="icon">👥</span> INSCRIPTION</a>
        <div class="lang-switch"><a href="#" class="active">Fran...</a><a href="#">Angl...</a></div>
    </div>
</header>