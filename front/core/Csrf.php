<?php

class Csrf
{
    private const TOKEN_KEY = '_csrf_token';
    private const SESSION_KEY = '_csrf_session';

    public static function token(): string
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return (string) $_SESSION[self::SESSION_KEY];
    }

    public static function verify(?string $token): bool
    {
        if (!isset($_SESSION[self::SESSION_KEY]) || $token === null || $token === '') {
            return false;
        }

        return hash_equals((string) $_SESSION[self::SESSION_KEY], $token);
    }

    public static function requestToken(): string
    {
        $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if ($header !== '') {
            return (string) $header;
        }

        return (string) ($_POST[self::TOKEN_KEY] ?? '');
    }

    public static function checkRequest(): bool
    {
        return self::verify(self::requestToken());
    }
}
