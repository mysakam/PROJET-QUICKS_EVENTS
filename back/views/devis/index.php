<?php $devis = $devis ?? []; ?>
<section class="card">
    <h1>Devis</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Reference</th>
                <th>Client</th>
                <th>Statut</th>
                <th>Montant</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($devis as $d): ?>
                <tr>
                    <td><?= (int) $d['id_devis'] ?></td>
                    <td><?= e($d['reference'] ?? '-') ?></td>
                    <td><?= e(($d['prenom'] ?? '') . ' ' . ($d['nom'] ?? '')) ?></td>
                    <td><?= e($d['statut'] ?? '-') ?></td>
                    <td><?= e((string) ($d['montant_total'] ?? '0')) ?> EUR</td>
                    <td><?= e($d['created_at'] ?? '-') ?></td>
                    <td><a href="<?= route('devis_show', ['id' => (int) $d['id_devis']]) ?>">Voir</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>