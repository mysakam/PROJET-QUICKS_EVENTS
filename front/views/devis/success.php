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

<link rel="stylesheet" href="/assets/css/devis-success.css">

<div class="success-page">
    <header class="success-header">
        <div class="success-header-inner">
            <div class="brand">
                <img src="/assets/images/logo.png" alt="Quick'Events">
                <div class="brand-name">QUICK'EVENTS</div>
            </div>

            <h1 class="success-title">DEVIS</h1>

            <div class="back-btn">
                <a href="<?= route('devis_index') ?>">RETOUR &rsaquo;</a>
            </div>
        </div>
    </header>

    <main class="success-main">
        <div class="success-box">
            <div class="success-message">Votre devis a bien été enregistré</div>

            <section class="success-meta">
                <p><strong>Référence :</strong> <?= e($devis['reference'] ?? $devis['id_devis'] ?? '') ?></p>
                <p><strong>Client :</strong> <?= e($clientNom ?: 'CLIENT') ?></p>
                <p><strong>Date :</strong> <?= e($dateDevis) ?></p>
                <p><strong>Statut :</strong> <?= e($devis['statut'] ?? 'en_attente') ?></p>
            </section>

            <section class="success-lines">
                <p><strong>Détail du devis :</strong></p>
                <ul>
                    <?php foreach ($lignes as $ligne): ?>
                    <li>
                        PRESTATION #<?= (int)($ligne['id_prestation'] ?? 0) ?>
                        — Qté <?= (int)($ligne['quantite'] ?? 0) ?>
                        — <?= number_format((float)($ligne['montant_ligne'] ?? 0), 2, ',', ' ') ?> €
                    </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <?php if (!empty($devis['message_client'])): ?>
            <section class="success-meta">
                <p><strong>Message client :</strong></p>
                <p><?= nl2br(e($devis['message_client'])) ?></p>
            </section>
            <?php endif; ?>

            <section class="success-total">
                <p><strong>Total devis :</strong> <?= number_format($total, 2, ',', ' ') ?> €</p>
            </section>

            <section class="success-actions">
                <div class="action-center">
                    <a class="pill-link" href="<?= route('devis_show', ['id' => $devis['id_devis']]) ?>">VOIR LE
                        DEVIS</a>
                </div>

                <div class="action-center">
                    <a class="pill-link" href="<?= route('devis_index') ?>">MES DEVIS</a>
                </div>
            </section>
        </div>
    </main>

    <footer class="success-footer">
        <div class="success-footer-top">
            <div>CONTACTS</div>
            <div>CVG</div>
            <div>FOLLOW US</div>
            <div>Mentions légales</div>
        </div>
    </footer>
</div>