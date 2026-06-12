<?php $categories = $categories ?? []; ?>
<section class="card">
    <h1>Catégories</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Slug</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $c): ?>
                <tr>
                    <td><?= (int) $c['id_categorie'] ?></td>
                    <td><?= e($c['nom'] ?? '') ?></td>
                    <td><?= e($c['slug'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>