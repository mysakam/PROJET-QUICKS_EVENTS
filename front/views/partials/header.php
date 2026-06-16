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
$isClientCatalogPage = $isClientLoggedIn && !$isAdmin && (
    $currentPath === $cataloguesPath
    || strpos($currentPath, $cataloguesPath . '/') === 0
    || strpos($currentPath, '/prestations/') === 0
);
$isClientEventPage = $isClientLoggedIn && !$isAdmin && in_array($currentPath, $clientEventPaths, true);

$hideEvents = $isAdminHome || $isAdminDashboard || $isAdminModulePage || $isAdminCataloguesPage || $isClientAccountPage || $isClientCatalogPage || $isClientEventPage;
$hideCatalogues = $isAdminModulePage || $isAdminCataloguesPage || $isClientAccountPage || $isClientCatalogPage || $isClientEventPage;
$hidePanier = $isAdminHome || $isAdminDashboard || $isAdminModulePage || $isAdminCataloguesPage || $isClientAccountPage || $isClientCatalogPage || $isClientEventPage;
$hideMesDevis = $hidePanier;
$hideMonCompte = $isAdminModulePage || $isClientAccountPage;
$hideAdminDashboard = $isAdminDashboard;

// Garder un comportement propre en évitant d'afficher un lien vers la page courante.
$hidePanier = $hidePanier || ($isClientLoggedIn && $currentPath === $panierPath);
$hideMesDevis = $hideMesDevis || ($isClientLoggedIn && $currentPath === $devisPath);
?>

<header class="site-header">
    <a href="<?= $homeUrl ?>" class="logo"><span> QUICK'EVENTS </span></a>
    <button class="menu-toggle" type="button" aria-label="Menu" aria-expanded="false" aria-controls="main-nav">
        <span></span>
        <span></span>
        <span></span>
    </button>
    <ul class="navbar" id="main-nav">
        <?php if (!$isHome): ?>
            <li><a href="<?= $homeUrl ?>" class="btn"><?= $lang === 'fr' ? 'ACCUEIL' : 'HOME' ?></a></li>
        <?php endif; ?>
        <?php if (!$hideEvents): ?>
            <li><a href="<?= $eventsLink ?>" class="btn"><?= $lang === 'fr' ? 'EVENEMENTS' : 'EVENTS' ?></a></li>
        <?php endif; ?>
        <?php if (!$hideCatalogues): ?>
            <li><a href="<?= $catalogueUrl ?>" class="btn"><?= $lang === 'fr' ? 'CATALOGUES' : 'CATALOGUES' ?></a></li>
        <?php endif; ?>

        <?php if ($isClientLoggedIn): ?>
            <?php if (!$hidePanier): ?>
                <li><a href="<?= route('panier') ?>" class="btn"><?= $lang === 'fr' ? 'PANIER' : 'CART' ?></a></li>
            <?php endif; ?>
            <?php if (!$hideMesDevis): ?>
                <li><a href="<?= route('devis_index') ?>" class="btn"><?= $lang === 'fr' ? 'MES DEVIS' : 'MY QUOTES' ?></a></li>
            <?php endif; ?>
            <?php if (!$hideMonCompte): ?>
                <li><a href="<?= route('account') ?>" class="btn"><?= $lang === 'fr' ? 'MON COMPTE' : 'MY ACCOUNT' ?></a></li>
            <?php endif; ?>
            <?php if ($isAdmin && !$hideAdminDashboard): ?>
                <li><a href="<?= route('admin_dashboard') ?>" class="btn"><?= $lang === 'fr' ? 'DASHBOARD ADMIN' : 'ADMIN DASHBOARD' ?></a></li>
            <?php endif; ?>
            <li><a href="<?= route('logout') ?>" class="btn"><?= $lang === 'fr' ? 'DECONNEXION' : 'LOG OUT' ?></a></li>
        <?php else: ?>
            <li><a href="<?= route('login') . $langQuery ?>" class="btn"><?= $lang === 'fr' ? 'CONNEXION' : 'LOGIN' ?></a></li>
            <li><a href="<?= route('register') . $langQuery ?>" class="btn"><?= $lang === 'fr' ? 'INSCRIPTION' : 'REGISTER' ?></a></li>
        <?php endif; ?>
        <li><a href="<?= route('home') . '?lang=' . $toggleLang ?>" class="btn-transcription"><?= $lang === 'fr' ? 'FR/EN' : 'EN/FR' ?></a></li>
    </ul>
</header>