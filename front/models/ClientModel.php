<?php

class ClientModel
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getPdo();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE id_client = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(string $nom, string $email, string $password): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO clients (nom, email, mot_de_passe)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$nom, $email, password_hash($password, PASSWORD_DEFAULT)]);
        return (int) $this->pdo->lastInsertId();
    }
}