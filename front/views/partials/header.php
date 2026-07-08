<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$langQuery = '?lang=' . $lang;
$toggleLang = $lang === 'fr' ? 'en' : 'fr';
$homeUrl = route('home') . $langQuery;
$catalogueUrl = route('catalogues') . $langQuery;
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$normalizePath = static function (string $path): string {
    if ($path === '') {
        return '/';
    }

    if ($path !== '/' && substr($path, -1) === '/') {
        return rtrim($path, '/');
    }

    return $path;
};

$currentPath = $normalizePath($currentPath);
$homePath = $normalizePath(route('home'));
$accountPath = $normalizePath(route('account'));
$cataloguesPath = $normalizePath(route('catalogues'));
$panierPath = $normalizePath(route('panier'));
$devisPath = $normalizePath(route('devis_index'));
$adminDashboardPath = $normalizePath(route('admin_dashboard'));
$loginPath = $normalizePath(route('login'));
$registerPath = $normalizePath(route('register'));
$clientEventPaths = [
    $normalizePath(route('event_mariage')),
    $normalizePath(route('event_anniversaire')),
    $normalizePath(route('event_soiree_theme')),
    $normalizePath(route('event_repas_seminaire')),
];

$isHome = ($currentPath === $homePath);
$eventsLink = $isHome ? '#evenements' : ($homeUrl . '#evenements');
$isClientLoggedIn = !empty($_SESSION['client']);
$isAdmin = $isClientLoggedIn && !empty($_SESSION['client']['is_admin']);

$isAdminHome = $isAdmin && $isHome;
$isAdminDashboard = $isAdmin && $currentPath === $adminDashboardPath;
$isAdminModulePage = $isAdmin && strpos($currentPath, '/admin/') === 0;
$isAdminCataloguesPage = $isAdmin && $currentPath === $cataloguesPath;
$isClientAccountPage = $isClientLoggedIn && $currentPath === $accountPath;
$isEventContextPage = in_array($currentPath, $clientEventPaths, true)
    || strpos($currentPath, '/events/') === 0;
$isClientCatalogPage = $isClientLoggedIn && !$isAdmin && (
    $currentPath === $cataloguesPath
    || strpos($currentPath, $cataloguesPath . '/') === 0
    || strpos($currentPath, '/prestations/') === 0
);
$isClientEventPage = $isClientLoggedIn && !$isAdmin && in_array($currentPath, $clientEventPaths, true);

$hideEvents = $isAdminHome || $isAdminDashboard || $isAdminModulePage || $isAdminCataloguesPage || $isClientAccountPage || $isClientCatalogPage || $isClientEventPage || $isEventContextPage;
$hideCatalogues = $isAdminModulePage || $isAdminCataloguesPage || $isClientAccountPage || $isClientCatalogPage || $isClientEventPage;
$hidePanier = $isAdminHome || $isAdminDashboard || $isAdminModulePage || $isAdminCataloguesPage || $isClientAccountPage || $isClientCatalogPage || $isClientEventPage;
$hideMesDevis = $hidePanier;
$hideMonCompte = $isAdminModulePage || $isClientAccountPage;
$hideAdminDashboard = $isAdminDashboard;

// Garder un comportement propre en évitant d'afficher un lien vers la page courante.
$hidePanier = $hidePanier || ($isClientLoggedIn && $currentPath === $panierPath);
$hideMesDevis = $hideMesDevis || ($isClientLoggedIn && $currentPath === $devisPath);

// Côté invité: masquer aussi les liens vers la page déjà ouverte.
$hideGuestLogin = !$isClientLoggedIn && ($currentPath === $loginPath);
$hideGuestRegister = !$isClientLoggedIn && ($currentPath === $registerPath);
$hideCatalogues = $hideCatalogues
    || ($currentPath === $cataloguesPath)
    || (strpos($currentPath, $cataloguesPath . '/') === 0)
    || (strpos($currentPath, '/prestations/') === 0);
?>

<header class="site-header">
    <a href="<?= $homeUrl ?>" class="logo" aria-label="Accueil QUICK'EVENTS">
        <img src="<?= asset('assets/images/logo-qe.png') ?>" alt="Logo QUICK'EVENTS" class="logo-image">
        <span> QUICK'EVENTS </span>
    </a>
    <button class="menu-toggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="main-nav">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <ul class="navbar" id="main-nav">
        <?php if (!$isHome): ?>
            <li><a href="<?= $homeUrl ?>" class="btn"><?= e(t('nav.home', $lang)) ?></a></li>
        <?php endif; ?>
        <?php if (!$hideEvents): ?>
            <li><a href="<?= $eventsLink ?>" class="btn"><?= e(t('nav.events', $lang)) ?></a></li>
        <?php endif; ?>
        <?php if (!$hideCatalogues): ?>
            <li><a href="<?= $catalogueUrl ?>" class="btn"><?= e(t('nav.catalogues', $lang)) ?></a></li>
        <?php endif; ?>

        <?php if ($isClientLoggedIn): ?>
            <?php if (!$hidePanier): ?>
                <li><a href="<?= route('panier') . $langQuery ?>" class="btn"><?= e(t('nav.cart', $lang)) ?></a></li>
            <?php endif; ?>
            <?php if (!$hideMesDevis): ?>
                <li><a href="<?= route('devis_index') . $langQuery ?>" class="btn"><?= e(t('nav.my_quotes', $lang)) ?></a></li>
            <?php endif; ?>
            <?php if (!$hideMonCompte): ?>
                <li><a href="<?= route('account') . $langQuery ?>" class="btn"><?= e(t('nav.my_account', $lang)) ?></a></li>
            <?php endif; ?>
            <?php if ($isAdmin && !$hideAdminDashboard): ?>
                <li><a href="<?= route('admin_dashboard') . $langQuery ?>" class="btn"><?= e(t('nav.admin', $lang)) ?></a></li>
            <?php endif; ?>
            <li><a href="<?= route('logout') . $langQuery ?>" class="btn"><?= e(t('nav.logout', $lang)) ?></a></li>
        <?php else: ?>
            <?php if (!$hideGuestLogin): ?>
                <li><a href="<?= route('login') . $langQuery ?>" class="btn"><?= e(t('nav.login', $lang)) ?></a></li>
            <?php endif; ?>
            <?php if (!$hideGuestRegister): ?>
                <li><a href="<?= route('register') . $langQuery ?>" class="btn"><?= e(t('nav.register', $lang)) ?></a></li>
            <?php endif; ?>
        <?php endif; ?>
        <li><a href="<?= route('home') . '?lang=' . $toggleLang ?>" class="btn-transcription"><?= e(t('nav.toggle', $lang)) ?></a></li>
    </ul>
</header>