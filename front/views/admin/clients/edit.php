<?php $client = $client ?? []; ?>
<section class="apropos">
    <div class="admin-media-shell admin-form-shell">
        <?php $lang = $lang ?? current_lang(); ?>
        <h2 class="titre-texte"><?= e(t('admin.clients.edit_title', $lang)) ?></h2>

        <?php if (!empty($_SESSION['error'])): ?>
            <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form class="admin-form" method="POST" action="<?= route('admin_clients_update', ['id' => $client['id_client']]) ?>?lang=<?= e($lang) ?>">
            <div class="admin-form-row"><label for="nom"><?= e(t('admin.clients.th_nom', $lang)) ?></label><input id="nom" name="nom" type="text" value="<?= e($client['nom'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="prenom"><?= e(t('admin.clients.th_prenom', $lang)) ?></label><input id="prenom" name="prenom" type="text" value="<?= e($client['prenom'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="email"><?= e(t('admin.clients.th_email', $lang)) ?></label><input id="email" name="email" type="email" value="<?= e($client['email'] ?? '') ?>" required></div>
            <div class="admin-form-row"><label for="telephone"><?= e(t('admin.clients.th_phone', $lang)) ?></label><input id="telephone" name="telephone" type="text" value="<?= e($client['telephone'] ?? '') ?>"></div>
            <div class="admin-form-actions">
                <button class="admin-btn" type="submit"><?= e(t('admin.clients.update', $lang)) ?></button>
                <a class="btn" href="<?= route('admin_clients_index') ?>?lang=<?= e($lang) ?>"><?= e(t('admin.back', $lang)) ?></a>
            </div>
        </form>
    </div>
</section>