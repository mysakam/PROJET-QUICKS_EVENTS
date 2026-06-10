<?php $users = $users ?? []; ?>
<section class="card">
    <h1>Clients</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Telephone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= (int) $u['id_client'] ?></td>
                    <td><?= e(($u['prenom'] ?? '') . ' ' . ($u['nom'] ?? '')) ?></td>
                    <td><?= e($u['email'] ?? '') ?></td>
                    <td><?= e($u['telephone'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>