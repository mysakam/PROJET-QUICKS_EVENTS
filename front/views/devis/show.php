<?php
$client = $_SESSION['client'] ?? [];
$clientNom = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
$dateDevis = !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : date('d/m/Y');
$total = (float)($devis['montant_total'] ?? 0);

function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<link rel="stylesheet" href="/assets/css/devis-show.css">

<div class="devis-page">
    <header class="devis-header">
        <div class="devis-header-inner">
            <div class="brand">
                <img src="/assets/images/logo.png" alt="Quick'Events">
                <div class="brand-name">QUICK'EVENTS</div>
            </div>

            <h1 class="devis-title">DEVIS</h1>

            <div class="back-btn">
                <a href="<?= route('devis_index') ?>">RETOUR &rsaquo;</a>
            </div>
        </div>
    </header>

    <main class="devis-main">
        <section class="devis-meta">
            <div>DEVIS N° <?= e($devis['reference'] ?? $devis['id_devis'] ?? '') ?></div>
            <div>NOM ET PRENOMS DU CLIENT: <?= e($clientNom ?: 'CLIENT') ?></div>
            <div>DATE: <?= e($dateDevis) ?></div>
        </section>

        <section class="devis-content">
            <div>
                <div class="bloc">
                    <h2 class="bloc-title">DETAIL DU DEVIS</h2>
                    <ul>
                        <?php foreach ($lignes as $ligne): ?>
                        <li>
                            PRESTATION #<?= (int)($ligne['id_prestation'] ?? 0) ?>
                            — Qté <?= (int)($ligne['quantite'] ?? 0) ?>
                            — <?= number_format((float)($ligne['prix_unitaire'] ?? 0), 2, ',', ' ') ?> €
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <?php if (!empty($devis['message_client'])): ?>
                <div class="bloc">
                    <h2 class="bloc-title">MESSAGE CLIENT</h2>
                    <ul>
                        <li><?= nl2br(e($devis['message_client'])) ?></li>
                    </ul>
                </div>
                <?php endif; ?>
            </div>

            <div class="totals">
                <?php foreach ($lignes as $index => $ligne): ?>
                <p>
                    SOUS-TOTAL <?= $index + 1 ?>:
                    <?= number_format((float)($ligne['montant_ligne'] ?? 0), 2, ',', ' ') ?> €
                </p>
                <?php endforeach; ?>

                <p class="grand-total">
                    TOTAL DEVIS (Hors taxes) :
                    <?= number_format($total, 2, ',', ' ') ?> €
                </p>
            </div>
        </section>

        <section class="devis-actions">
            <div class="action-center">
                <a class="pill-link" href="<?= route('devis_checkout') ?>">MODIFIER</a>
            </div>

            <div class="action-center">
                <a class="pill-link" href="#" onclick="return false;">VALIDER</a>
            </div>

            <div class="action-center">
                <a class="pill-link" href="<?= route('devis_index') ?>">ANNULER</a>
            </div>
        </section>
    </main>

    <footer class="devis-footer">
        <div class="devis-footer-top">
            <div>CONTACTS</div>
            <div>CVG</div>
            <div>FOLLOW US</div>
            <div>Mentions légales</div>
        </div>
    </footer>
</div>