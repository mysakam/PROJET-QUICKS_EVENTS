<?php
$client = $_SESSION['client'] ?? [];
$clientNom = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
$dateCreationDevis = !empty($devis['created_at']) ? date('d/m/Y', strtotime($devis['created_at'])) : date('d/m/Y');
$dateReservation = !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : '-';
$total = (float)($devis['montant_total'] ?? 0);
$facture = $facture ?? null;
$isValidatedByClient = (($devis['statut'] ?? '') === 'valide_client');

?>

<section class="devis-meta">
    <div>PROPOSITION DE DEVIS N° <?= e($devis['reference'] ?? $devis['id_devis'] ?? '') ?></div>
    <div>NOM ET PRENOMS DU CLIENT: <?= e($clientNom ?: 'CLIENT') ?></div>
    <div>DATE DE CREATION DE LA PROPOSITION: <?= e($dateCreationDevis) ?></div>
    <div>DATE DE RÉSERVATION DE L'ÉVÉNEMENT: <?= e($dateReservation) ?></div>
</section>

<section class="devis-content">
    <div>
        <div class="bloc">
            <h2 class="bloc-title">DETAIL DE LA PROPOSITION DE DEVIS</h2>
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

        <?php if (!empty($facture)): ?>
            <div class="bloc">
                <h2 class="bloc-title">FACTURE PROPOSEE</h2>
                <ul>
                    <li>Reference : <?= e($facture['reference']) ?></li>
                    <li>Statut : <?= e($facture['statut']) ?></li>
                    <li>Montant TTC : <?= number_format((float)($facture['montant_ttc'] ?? 0), 2, ',', ' ') ?> €</li>
                </ul>
                <?php if (($facture['statut'] ?? '') === 'en_attente_validation'): ?>
                    <p>La facture est en attente de validation par l'administration. Elle sera envoyee par mail une fois approuvee.</p>
                <?php endif; ?>
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
            TOTAL PROPOSITION (Hors taxes) :
            <?= number_format($total, 2, ',', ' ') ?> €
        </p>
    </div>
</section>

<section class="devis-actions">
    <?php if ($isValidatedByClient): ?>
        <div class="action-center">
            <form method="POST" action="<?= route('devis_cancel', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link">ANNULER</button>
            </form>
        </div>

        <div class="action-center">
            <form method="POST" action="<?= route('devis_reopen', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link">REPRENDRE LA PROPOSITION</button>
            </form>
        </div>
    <?php else: ?>
        <div class="action-center">
            <form method="POST" action="<?= route('devis_reopen', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link">MODIFIER</button>
            </form>
        </div>

        <div class="action-center">
            <form method="POST" action="<?= route('devis_validate', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link">VALIDER ET DEMANDER LA FACTURE</button>
            </form>
        </div>

        <div class="action-center">
            <a class="pill-link" href="<?= route('devis_index') ?>">ANNULER</a>
        </div>
    <?php endif; ?>
</section>