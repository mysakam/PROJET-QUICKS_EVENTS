<?php
$client = $_SESSION['client'] ?? [];
$clientNom = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
$dateDevis = !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : date('d/m/Y');
$total = (float)($devis['montant_total'] ?? 0);

?>

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