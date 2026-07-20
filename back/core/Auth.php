<?php

class Auth
{
    private static ?string $lastFailureReason = null;

    public static function check(): bool
    {
        return !empty($_SESSION['client']);
    }

    public static function user(): ?array
    {
        return $_SESSION['client'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return !empty($_SESSION['client']['is_admin']);
    }

    public static function failureReason(): ?string
    {
        return self::$lastFailureReason;
    }

    public static function attempt(string $email, string $password): bool
    {
        self::$lastFailureReason = null;
        $email = trim($email);
        if ($email === '' || $password === '') {
            self::$lastFailureReason = 'missing_credentials';
            return false;
        }

        try {
            $stmt = Database::getPdo()->prepare('SELECT * FROM clients WHERE email = :email LIMIT 1');
            $stmt->execute(['email' => $email]);
            $client = $stmt->fetch();
        } catch (Throwable) {
            self::$lastFailureReason = 'storage_error';
            return false;
        }

        if (!$client) {
            self::$lastFailureReason = 'account_not_found';
            return false;
        }

        if (!password_verify($password, (string) ($client['mot_de_passe'] ?? ''))) {
            self::$lastFailureReason = 'invalid_password';
            return false;
        }

        $app = require __DIR__ . '/../config/app.php';
        $adminEmails = array_map('strtolower', $app['admin_emails'] ?? []);

        $_SESSION['client'] = [
            'id_client' => (int) ($client['id_client'] ?? 0),
            'nom' => (string) ($client['nom'] ?? ''),
            'prenom' => (string) ($client['prenom'] ?? ''),
            'email' => (string) ($client['email'] ?? ''),
            'is_admin' => in_array(strtolower((string) ($client['email'] ?? '')), $adminEmails, true),
        ];

        return true;
    }

    public static function logout(): void
    {
        unset($_SESSION['client']);
        self::$lastFailureReason = null;
    }
}
