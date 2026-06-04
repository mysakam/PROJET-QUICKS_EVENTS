<?php

class EventMediaModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function findByTheme(string $themeSlug, string $lang = 'fr'): array
    {
        $titleColumn = $lang === 'en' ? 'title_en' : 'title_fr';
        $descriptionColumn = $lang === 'en' ? 'description_en' : 'description_fr';

        $sql = "SELECT
                    id_media,
                    theme_slug,
                    media_type,
                    media_url,
                    {$titleColumn} AS title,
                    {$descriptionColumn} AS description,
                    position
                FROM event_medias
                WHERE theme_slug = :theme_slug
                  AND is_active = 1
                ORDER BY position ASC, id_media ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['theme_slug' => $themeSlug]);

        return $stmt->fetchAll();
    }

    public function findFirstByThemes(array $themeSlugs, string $lang = 'fr'): array
    {
        $titleColumn = $lang === 'en' ? 'title_en' : 'title_fr';
        $descriptionColumn = $lang === 'en' ? 'description_en' : 'description_fr';

        $themeSlugs = array_values(array_unique(array_filter(array_map('trim', $themeSlugs))));
        if (empty($themeSlugs)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($themeSlugs), '?'));

        $sql = "SELECT
                    id_media,
                    theme_slug,
                    media_type,
                    media_url,
                    {$titleColumn} AS title,
                    {$descriptionColumn} AS description,
                    position
                FROM event_medias
                WHERE theme_slug IN ($placeholders)
                  AND is_active = 1
                ORDER BY theme_slug ASC, position ASC, id_media ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($themeSlugs);

        $map = [];
        foreach ($stmt->fetchAll() as $row) {
            $slug = $row['theme_slug'];
            if (!isset($map[$slug])) {
                $map[$slug] = $row;
            }
        }

        return $map;
    }

    public function findAll(?string $themeSlug = null): array
    {
        $sql = "SELECT
                    id_media,
                    theme_slug,
                    media_type,
                    media_url,
                    title_fr,
                    title_en,
                    description_fr,
                    description_en,
                    position,
                    is_active,
                    created_at,
                    updated_at
                FROM event_medias";

        $params = [];

        if (!empty($themeSlug)) {
            $sql .= " WHERE theme_slug = :theme_slug";
            $params['theme_slug'] = $themeSlug;
        }

        $sql .= " ORDER BY theme_slug ASC, position ASC, id_media ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public function findById(int $idMedia): ?array
    {
        $stmt = $this->pdo->prepare("SELECT
                                        id_media,
                                        theme_slug,
                                        media_type,
                                        media_url,
                                        title_fr,
                                        title_en,
                                        description_fr,
                                        description_en,
                                        position,
                                        is_active,
                                        created_at,
                                        updated_at
                                     FROM event_medias
                                     WHERE id_media = :id_media
                                     LIMIT 1");
        $stmt->execute(['id_media' => $idMedia]);
        $media = $stmt->fetch();

        return $media ?: null;
    }

    public function create(array $data): int
    {
        $sql = "INSERT INTO event_medias (
                    theme_slug,
                    media_type,
                    media_url,
                    title_fr,
                    title_en,
                    description_fr,
                    description_en,
                    position,
                    is_active
                ) VALUES (
                    :theme_slug,
                    :media_type,
                    :media_url,
                    :title_fr,
                    :title_en,
                    :description_fr,
                    :description_en,
                    :position,
                    :is_active
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'theme_slug' => $data['theme_slug'],
            'media_type' => $data['media_type'] ?? 'image',
            'media_url' => $data['media_url'],
            'title_fr' => $data['title_fr'],
            'title_en' => $data['title_en'],
            'description_fr' => $data['description_fr'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'position' => (int)($data['position'] ?? 1),
            'is_active' => (int)($data['is_active'] ?? 1),
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $idMedia, array $data): bool
    {
        $sql = "UPDATE event_medias
                SET theme_slug = :theme_slug,
                    media_type = :media_type,
                    media_url = :media_url,
                    title_fr = :title_fr,
                    title_en = :title_en,
                    description_fr = :description_fr,
                    description_en = :description_en,
                    position = :position,
                    is_active = :is_active
                WHERE id_media = :id_media";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id_media' => $idMedia,
            'theme_slug' => $data['theme_slug'],
            'media_type' => $data['media_type'] ?? 'image',
            'media_url' => $data['media_url'],
            'title_fr' => $data['title_fr'],
            'title_en' => $data['title_en'],
            'description_fr' => $data['description_fr'] ?? null,
            'description_en' => $data['description_en'] ?? null,
            'position' => (int)($data['position'] ?? 1),
            'is_active' => (int)($data['is_active'] ?? 1),
        ]);
    }

    public function delete(int $idMedia): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM event_medias WHERE id_media = :id_media');

        return $stmt->execute(['id_media' => $idMedia]);
    }
}
