<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$client = $_SESSION['client'] ?? [];
$clientNom = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
$dateCreationDevis = !empty($devis['created_at']) ? date('d/m/Y', strtotime($devis['created_at'])) : date('d/m/Y');
$dateReservation = !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : '-';
$total = (float)($devis['montant_total'] ?? 0);
$facture = $facture ?? null;
$isValidatedByClient = (($devis['statut'] ?? '') === 'valide_client');

$txt = [
    'fr' => [
        'proposal_num' => 'PROPOSITION DE DEVIS N°',
        'client' => 'NOM ET PRENOMS DU CLIENT',
        'date_creation' => 'DATE DE CREATION DE LA PROPOSITION',
        'date_event' => "DATE DE RÉSERVATION DE L'ÉVÉNEMENT",
        'detail' => 'DETAIL DE LA PROPOSITION DE DEVIS',
        'message' => 'MESSAGE CLIENT',
        'invoice' => 'FACTURE PROPOSEE',
        'waiting' => "La facture est en attente de validation par l'administration. Elle sera envoyee par mail une fois approuvee.",
        'subtotal' => 'SOUS-TOTAL',
        'total' => 'TOTAL PROPOSITION (Hors taxes) :',
        'annuler' => 'ANNULER',
        'reopen' => 'REPRENDRE LA PROPOSITION',
        'modifier' => 'MODIFIER',
        'validate' => 'VALIDER ET DEMANDER LA FACTURE',
    ],
    'en' => [
        'proposal_num' => 'QUOTE PROPOSAL N°',
        'client' => 'CLIENT NAME',
        'date_creation' => 'PROPOSAL CREATION DATE',
        'date_event' => 'EVENT BOOKING DATE',
        'detail' => 'QUOTE PROPOSAL DETAILS',
        'message' => 'CLIENT MESSAGE',
        'invoice' => 'PROPOSED INVOICE',
        'waiting' => 'The invoice is waiting for administration approval. It will be emailed once approved.',
        'subtotal' => 'SUBTOTAL',
        'total' => 'QUOTE PROPOSAL TOTAL (Excl. tax) :',
        'annuler' => 'CANCEL',
        'reopen' => 'REOPEN PROPOSAL',
        'modifier' => 'EDIT',
        'validate' => 'VALIDATE AND REQUEST INVOICE',
    ],
];
$t = $txt[$lang];
?>

<section class="devis-meta">
    <div><?= e($t['proposal_num']) ?> <?= e($devis['reference'] ?? $devis['id_devis'] ?? '') ?></div>
    <div><?= e($t['client']) ?>: <?= e($clientNom ?: 'CLIENT') ?></div>
    <div><?= e($t['date_creation']) ?>: <?= e($dateCreationDevis) ?></div>
    <div><?= e($t['date_event']) ?>: <?= e($dateReservation) ?></div>
</section>

<section class="devis-content">
    <div>
        <div class="bloc">
            <h2 class="bloc-title"><?= e($t['detail']) ?></h2>
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
                <h2 class="bloc-title"><?= e($t['message']) ?></h2>
                <ul>
                    <li><?= nl2br(e($devis['message_client'])) ?></li>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($facture)): ?>
            <div class="bloc">
                <h2 class="bloc-title"><?= e($t['invoice']) ?></h2>
                <ul>
                    <li>Reference : <?= e($facture['reference']) ?></li>
                    <li>Statut : <?= e($facture['statut']) ?></li>
                    <li>Montant TTC : <?= number_format((float)($facture['montant_ttc'] ?? 0), 2, ',', ' ') ?> €</li>
                </ul>
                <?php if (($facture['statut'] ?? '') === 'en_attente_validation'): ?>
                    <p><?= e($t['waiting']) ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="totals">
        <?php foreach ($lignes as $index => $ligne): ?>
            <p>
                <?= e($t['subtotal']) ?> <?= $index + 1 ?>:
                <?= number_format((float)($ligne['montant_ligne'] ?? 0), 2, ',', ' ') ?> €
            </p>
        <?php endforeach; ?>

        <p class="grand-total">
            <?= e($t['total']) ?>
            <?= number_format($total, 2, ',', ' ') ?> €
        </p>
    </div>
</section>

<section class="devis-actions">
    <?php if ($isValidatedByClient): ?>
        <div class="action-center">
            <form method="POST" action="<?= route('devis_cancel', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link"><?= e($t['annuler']) ?></button>
            </form>
        </div>

        <div class="action-center">
            <form method="POST" action="<?= route('devis_reopen', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link"><?= e($t['reopen']) ?></button>
            </form>
        </div>
    <?php else: ?>
        <div class="action-center">
            <form method="POST" action="<?= route('devis_reopen', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link"><?= e($t['modifier']) ?></button>
            </form>
        </div>

        <div class="action-center">
            <form method="POST" action="<?= route('devis_validate', ['id' => $devis['id_devis']]) ?>" data-fetch-form>
                <button type="submit" class="pill-link"><?= e($t['validate']) ?></button>
            </form>
        </div>

        <div class="action-center">
            <a class="pill-link" href="<?= route('devis_index') . '?lang=' . $lang ?>"><?= e($lang === 'fr' ? 'ANNULER' : 'CANCEL') ?></a>
        </div>
    <?php endif; ?>
</section>