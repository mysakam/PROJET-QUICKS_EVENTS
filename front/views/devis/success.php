<?php
$client = $_SESSION['client'] ?? [];
$clientNom = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
$dateDevis = !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : date('d/m/Y');
$total = (float)($devis['montant_total'] ?? 0);
$facture = $facture ?? null;

?>

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

    <?php if (!empty($facture)): ?>
        <section class="success-meta">
            <p><strong>Facture proposée :</strong> <?= e($facture['reference']) ?></p>
            <p><strong>Statut :</strong> <?= e($facture['statut']) ?></p>
            <p><strong>Montant TTC :</strong> <?= number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ') ?> €</p>
            <p>Elle sera vérifiée/modifiée par l'administration avant envoi par mail.</p>
        </section>
    <?php endif; ?>

    <section class="success-total">
        <p><strong>Total devis :</strong> <?= number_format($total, 2, ',', ' ') ?> €</p>
    </section>

    <section class="success-actions">
        <div class="action-center">
            <a class="pill-link" href="<?= route('devis_show', ['id' => $devis['id_devis']]) ?>">VOIR LE DEVIS</a>
        </div>

        <div class="action-center">
            <a class="pill-link" href="<?= route('devis_index') ?>">MES DEVIS</a>
        </div>
    </section>
</div>