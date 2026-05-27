<?php

class DevisLigneModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function create(array $data): void
    {
        $sql = "INSERT INTO devis_lignes (
                    id_devis,
                    id_prestation,
                    quantite,
                    prix_unitaire,
                    montant_ligne
                ) VALUES (
                    :id_devis,
                    :id_prestation,
                    :quantite,
                    :prix_unitaire,
                    :montant_ligne
                )";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_devis' => $data['id_devis'],
            'id_prestation' => $data['id_prestation'],
            'quantite' => $data['quantite'],
            'prix_unitaire' => $data['prix_unitaire'],
            'montant_ligne' => $data['montant_ligne'],
        ]);
    }

    public function findByDevisId(int $devisId): array
    {
        $sql = "SELECT
                    dl.*,
                    p.nom
                FROM devis_lignes dl
                INNER JOIN prestations p ON p.id_prestation = dl.id_prestation
                WHERE dl.id_devis = :id_devis
                ORDER BY dl.id_ligne_devis ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id_devis' => $devisId]);

        return $stmt->fetchAll();
    }
}