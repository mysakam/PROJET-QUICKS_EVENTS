<?php $prestations = $prestations ?? []; ?>
<section class="card">
    <h1>Prestations</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Categorie</th>
                <th>Prix unitaire</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($prestations as $p): ?>
                <tr>
                    <td><?= (int) $p['id_prestation'] ?></td>
                    <td><?= e($p['nom'] ?? '') ?></td>
                    <td><?= e($p['categorie'] ?? '-') ?></td>
                    <td><?= e((string) ($p['prix_unitaire'] ?? '0')) ?> EUR</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>