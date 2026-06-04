<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <h2 class="titre-texte"><span>M</span>on compte client</h2>

        <div class="admin-table-wrap">
            <table class="admin-table" style="min-width: 0;">
                <tbody>
                    <tr>
                        <th>Nom</th>
                        <td><?= e($client['nom'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Prenom</th>
                        <td><?= e($client['prenom'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= e($client['email'] ?? '') ?></td>
                    </tr>
                    <tr>
                        <th>Telephone</th>
                        <td><?= e($client['telephone'] ?? '-') ?></td>
                    </tr>
                    <tr>
                        <th>Compte cree le</th>
                        <td><?= e($client['created_at'] ?? '-') ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="admin-form-actions" style="margin-top: 18px;">
            <a class="btn" href="<?= route('catalogues') ?>">Voir les catalogues</a>
            <a class="btn" href="<?= route('devis_index') ?>">Mes devis</a>
        </div>
    </div>
</section>