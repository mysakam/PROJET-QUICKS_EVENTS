<?php
$devisItem = $devisItem ?? null;
$lignes = $lignes ?? [];
?>
<section class="card">
    <h1>Détail devis</h1>

    <?php if (!$devisItem): ?>
        <p>Devis introuvable.</p>
    <?php else: ?>
        <p><strong>Référence:</strong> <?= e($devisItem['reference'] ?? '-') ?></p>
        <p><strong>Client:</strong> <?= e(($devisItem['prenom'] ?? '') . ' ' . ($devisItem['nom'] ?? '')) ?></p>
        <p><strong>Email:</strong> <?= e($devisItem['email'] ?? '-') ?></p>
        <p><strong>Statut:</strong> <?= e($devisItem['statut'] ?? '-') ?></p>
        <p><strong>Montant total:</strong> <?= e((string) ($devisItem['montant_total'] ?? '0')) ?> EUR</p>

        <h2>Lignes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prestation</th>
                    <th>Quantité</th>
                    <th>Prix unitaire</th>
                    <th>Montant ligne</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lignes as $l): ?>
                    <tr>
                        <td><?= (int) $l['id_ligne'] ?></td>
                        <td><?= e($l['prestation'] ?? '-') ?></td>
                        <td><?= (int) ($l['quantite'] ?? 0) ?></td>
                        <td><?= e((string) ($l['prix_unitaire'] ?? '0')) ?> EUR</td>
                        <td><?= e((string) ($l['montant_ligne'] ?? '0')) ?> EUR</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>