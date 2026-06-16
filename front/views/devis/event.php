<?php
$lang = $lang ?? (($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr');
$langQuery = '?lang=' . $lang;
$eventRequest = $eventRequest ?? [];
$oldEventRequest = $oldEventRequest ?? [];

$txt = [
    'fr' => [
        'title' => 'Créer mon événement',
        'intro' => 'Décrivez votre événement une première fois. Ces informations seront reprises dans votre proposition de devis finale et vous permettront de préparer une demande plus précise.',
        'highlight1' => "Type d'événement souhaité",
        'highlight2' => "Nombre d'invités",
        'highlight3' => 'Budget estimatif',
        'note' => 'Votre fiche est enregistrée pour votre compte client et pourra être utilisée au moment de la proposition de devis.',
        'card_title' => 'Ma fiche événement',
        'type' => "Type d'événement",
        'type_ph' => 'mariage, anniversaire, séminaire...',
        'guests' => "Nombre d'invités",
        'budget' => 'Budget estimatif',
        'save' => 'Enregistrer ma fiche',
        'catalogues' => 'Voir les catalogues',
    ],
    'en' => [
        'title' => 'Create my event',
        'intro' => 'Describe your event once. This information will be reused in your final quote proposal and will help you prepare a more precise request.',
        'highlight1' => 'Desired event type',
        'highlight2' => 'Number of guests',
        'highlight3' => 'Estimated budget',
        'note' => 'Your form is saved to your client account and can be used when preparing the quote proposal.',
        'card_title' => 'My event form',
        'type' => 'Event type',
        'type_ph' => 'wedding, birthday, seminar...',
        'guests' => 'Number of guests',
        'budget' => 'Estimated budget',
        'save' => 'Save my form',
        'catalogues' => 'View catalogues',
    ],
];
$t = $txt[$lang];
?>

<section class="apropos auth-section">
    <div class="admin-media-shell auth-shell event-request-shell">
        <div class="auth-copy">
            <p class="auth-kicker">QUICK'EVENTS</p>
            <h2 class="titre-texte"><?= e($t['title']) ?></h2>
            <p><?= e($t['intro']) ?></p>

            <div class="auth-highlights">
                <div class="auth-highlight"><?= e($t['highlight1']) ?></div>
                <div class="auth-highlight"><?= e($t['highlight2']) ?></div>
                <div class="auth-highlight"><?= e($t['highlight3']) ?></div>
            </div>

            <div class="event-request-note">
                <?= e($t['note']) ?>
            </div>
        </div>

        <div class="admin-media-shell admin-form-shell auth-card">
            <h3 class="auth-card-title"><?= e($t['card_title']) ?></h3>

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
                    <label for="type_evenement"><?= e($t['type']) ?></label>
                    <input type="text" id="type_evenement" name="type_evenement" placeholder="<?= e($t['type_ph']) ?>" required value="<?= e($oldEventRequest['type_evenement'] ?? $eventRequest['type_evenement'] ?? '') ?>">
                </div>

                <div class="auth-form-grid">
                    <div class="admin-form-row">
                        <label for="nb_personnes"><?= e($t['guests']) ?></label>
                        <input type="number" id="nb_personnes" name="nb_personnes" min="1" required value="<?= e($oldEventRequest['nb_personnes'] ?? $eventRequest['nb_personnes'] ?? '') ?>">
                    </div>
                    <div class="admin-form-row">
                        <label for="budget"><?= e($t['budget']) ?></label>
                        <input type="text" id="budget" name="budget" placeholder="ex: 5000 EUR" required value="<?= e($oldEventRequest['budget'] ?? $eventRequest['budget'] ?? '') ?>">
                    </div>
                </div>

                <div class="admin-form-actions">
                    <button class="admin-btn" type="submit"><?= e($t['save']) ?></button>
                    <a class="btn" href="<?= route('catalogues') . $langQuery ?>"><?= e($t['catalogues']) ?></a>
                </div>
            </form>
        </div>
    </div>
</section>