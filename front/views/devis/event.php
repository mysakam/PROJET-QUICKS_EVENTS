<?php
$lang = $lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr');
$langQuery = '?lang=' . $lang;
$eventRequest = $eventRequest ?? [];
$oldEventRequest = $oldEventRequest ?? [];
?>

<section class="apropos auth-section">
    <div class="admin-media-shell auth-shell event-request-shell">
        <div class="auth-copy">
            <p class="auth-kicker">QUICK'EVENTS</p>
            <h2 class="titre-texte"><span>C</span>réer mon événement</h2>
            <p>Décrivez votre événement une première fois. Ces informations seront reprises dans votre devis final et vous permettront de préparer une demande plus précise.</p>

            <div class="auth-highlights">
                <div class="auth-highlight">Type d'événement souhaité</div>
                <div class="auth-highlight">Nombre d'invités</div>
                <div class="auth-highlight">Budget estimatif</div>
            </div>

            <div class="event-request-note">
                Votre fiche est enregistrée pour votre compte client et pourra être utilisée au moment du devis.
            </div>
        </div>

        <div class="admin-media-shell admin-form-shell auth-card">
            <h3 class="auth-card-title">Ma fiche événement</h3>

            <?php if (!empty($_SESSION['error'])): ?>
                <p class="admin-alert admin-alert-error"><?= e($_SESSION['error']) ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <p class="admin-alert admin-alert-success"><?= e($_SESSION['success']) ?></p>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form class="admin-form" method="POST" action="<?= route('mon_evenement_post') . $langQuery ?>" data-fetch-form>
                <div class="admin-form-row">
                    <label for="type_evenement">Type d'événement</label>
                    <input type="text" id="type_evenement" name="type_evenement" placeholder="mariage, anniversaire, séminaire..." required value="<?= e($oldEventRequest['type_evenement'] ?? $eventRequest['type_evenement'] ?? '') ?>">
                </div>

                <div class="auth-form-grid">
                    <div class="admin-form-row">
                        <label for="nb_personnes">Nombre d'invités</label>
                        <input type="number" id="nb_personnes" name="nb_personnes" min="1" required value="<?= e($oldEventRequest['nb_personnes'] ?? $eventRequest['nb_personnes'] ?? '') ?>">
                    </div>
                    <div class="admin-form-row">
                        <label for="budget">Budget estimatif</label>
                        <input type="text" id="budget" name="budget" placeholder="ex: 5000 EUR" required value="<?= e($oldEventRequest['budget'] ?? $eventRequest['budget'] ?? '') ?>">
                    </div>
                </div>

                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit">Enregistrer ma fiche</button>
                    <a class="btn" href="<?= route('catalogues') . $langQuery ?>">Voir les catalogues</a>
                </div>
            </form>
        </div>
    </div>
</section>