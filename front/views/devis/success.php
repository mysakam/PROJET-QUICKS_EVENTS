<?php
$lang = ($lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr'));
$client = $_SESSION['client'] ?? [];
$clientNom = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
$dateCreationDevis = !empty($devis['created_at']) ? date('d/m/Y', strtotime($devis['created_at'])) : date('d/m/Y');
$dateReservation = !empty($devis['date_evenement']) ? date('d/m/Y', strtotime($devis['date_evenement'])) : '-';
$total = (float)($devis['montant_total'] ?? 0);
$lignes = $lignes ?? [];
$facture = $facture ?? null;

$txt = [
    'fr' => [
        'message' => 'Votre proposition de devis a bien ete enregistree',
        'reference' => 'Référence',
        'client' => 'Client',
        'date_creation' => 'Date de création de la proposition',
        'date_event' => "Date de réservation de l'événement",
        'status' => 'Statut',
        'detail' => 'Détail de la proposition de devis :',
        'message_client' => 'Message client :',
        'invoice' => 'Facture proposée :',
        'waiting' => "Elle sera vérifiée/modifiée par l'administration avant envoi par mail.",
        'total' => 'Total proposition :',
        'back' => 'MES DEVIS',
        'validate_now' => 'VALIDER ET DEMANDER LA FACTURE',
    ],
    'en' => [
        'message' => 'Your quote proposal has been saved',
        'reference' => 'Reference',
        'client' => 'Client',
        'date_creation' => 'Proposal creation date',
        'date_event' => 'Event booking date',
        'status' => 'Status',
        'detail' => 'Quote proposal details:',
        'message_client' => 'Client message:',
        'invoice' => 'Proposed invoice:',
        'waiting' => 'It will be checked/updated by administration before being emailed.',
        'total' => 'Proposal total:',
        'back' => 'MY QUOTES',
        'validate_now' => 'VALIDATE AND REQUEST INVOICE',
    ],
];
$t = $txt[$lang];
?>

<div class="success-box">
    <div class="success-message"><?= e($t['message']) ?></div>

    <section class="success-meta">
        <p><strong><?= e($t['reference']) ?> :</strong> <?= e($devis['reference'] ?? $devis['id_devis'] ?? '') ?></p>
        <p><strong><?= e($t['client']) ?> :</strong> <?= e($clientNom ?: 'CLIENT') ?></p>
        <p><strong><?= e($t['date_creation']) ?> :</strong> <?= e($dateCreationDevis) ?></p>
        <p><strong><?= e($t['date_event']) ?> :</strong> <?= e($dateReservation) ?></p>
        <p><strong><?= e($t['status']) ?> :</strong> <?= e($devis['statut'] ?? 'en_attente') ?></p>
    </section>

    <section class="success-lines">
        <p><strong><?= e($t['detail']) ?></strong></p>
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
            <p><strong><?= e($t['message_client']) ?></strong></p>
            <p><?= nl2br(e($devis['message_client'])) ?></p>
        </section>
    <?php endif; ?>

    <?php if (!empty($facture)): ?>
        <section class="success-meta">
            <p><strong><?= e($t['invoice']) ?></strong> <?= e($facture['reference']) ?></p>
            <p><strong><?= e($t['status']) ?> :</strong> <?= e($facture['statut']) ?></p>
            <p><strong>Montant TTC :</strong> <?= number_format((float) ($facture['montant_ttc'] ?? 0), 2, ',', ' ') ?> €</p>
            <p><?= e($t['waiting']) ?></p>
        </section>
    <?php endif; ?>

    <section class="success-total">
        <p><strong><?= e($t['total']) ?></strong> <?= number_format($total, 2, ',', ' ') ?> €</p>
    </section>

    <section class="success-actions">
        <div class="action-center">
            <?php if (($devis['statut'] ?? '') !== 'valide_client'): ?>
                <form method="post" action="<?= route('devis_validate', ['id' => (int) ($devis['id_devis'] ?? 0)]) . '?lang=' . $lang ?>" style="display:inline-block; margin-right: 10px;">
                    <input type="hidden" name="_csrf_token" value="<?= e(Csrf::token()) ?>">
                    <button class="pill-link" type="submit"><?= e($t['validate_now']) ?></button>
                </form>
            <?php endif; ?>
            <a class="pill-link" href="<?= route('devis_index') . '?lang=' . $lang ?>"><?= e($t['back']) ?></a>
        </div>
    </section>
</div>