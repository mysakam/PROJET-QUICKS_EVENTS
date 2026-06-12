<section class="apropos">
    <div class="admin-media-shell">
        <h2 class="titre-texte"><span>D</span>ashboard admin</h2>
        <p>Choisissez un module pour gérer les données.</p>

        <div class="theme-grid">
            <article class="polaroid event-polaroid">
                <h3>Médias</h3>
                <p class="card-text">Gérer les photos/vidéos des pages événements.</p>
                <a class="btn" href="<?= route('admin_event_medias') ?>">Ouvrir</a>
            </article>
            <article class="polaroid event-polaroid">
                <h3>Prestataires</h3>
                <p class="card-text">CRUD des prestataires et leurs informations.</p>
                <a class="btn" href="<?= route('admin_prestataires_index') ?>">Ouvrir</a>
            </article>
            <article class="polaroid event-polaroid">
                <h3>Factures</h3>
                <p class="card-text">Gestion des factures liées aux devis et aux prestataires.</p>
                <a class="btn" href="<?= route('admin_factures_index') ?>">Ouvrir</a>
            </article>
            <article class="polaroid event-polaroid">
                <h3>Clients</h3>
                <p class="card-text">CRUD des comptes clients.</p>
                <a class="btn" href="<?= route('admin_clients_index') ?>">Ouvrir</a>
            </article>
            <article class="polaroid event-polaroid">
                <h3>Statistiques</h3>
                <p class="card-text">Devis, CA, statuts et tops par entité.</p>
                <a class="btn" href="<?= route('admin_stats_index') ?>">Ouvrir</a>
            </article>
        </div>
    </div>
</section>