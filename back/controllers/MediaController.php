<?php

class MediaController extends Controller
{
    public function index(): void
    {
        $medias = [];
        $notice = null;

        try {
            $stmt = Database::getPdo()->query('SELECT id_media, theme_slug, type_media, url_media, titre, is_active FROM event_medias ORDER BY id_media DESC LIMIT 100');
            $medias = $stmt->fetchAll();
        } catch (Throwable) {
            $notice = 'Table event_medias indisponible.';
        }

        $this->render('media/index', [
            'pageTitle' => 'Medias',
            'medias' => $medias,
            'notice' => $notice,
        ]);
    }

    public function create(): void
    {
        $this->render('media/create', ['pageTitle' => 'Ajouter media']);
    }

    public function edit(string $id): void
    {
        $this->render('media/edit', [
            'pageTitle' => 'Editer media',
            'mediaId' => (int) $id,
        ]);
    }
}
