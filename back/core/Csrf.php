<?php

class Csrf
{
    private const TOKEN_KEY = '_csrf_token';
    private const SESSION_KEY = '_csrf_session';

    public static function generateToken(): string
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function token(): string
    {
        return self::generateToken();
    }

    public static function field(): string
    {
        return sprintf(
            '<input type="hidden" name="%s" value="%s">',
            self::TOKEN_KEY,
            htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8')
        );
    }

    public static function verify(string $token): bool
    {
        if (!isset($_SESSION[self::SESSION_KEY])) {
            return false;
        }

        return hash_equals($_SESSION[self::SESSION_KEY], $token);
    }

    public static function requestToken(): string
    {
        $header = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if ($header !== '') {
            return (string) $header;
        }

        return (string) ($_POST[self::TOKEN_KEY] ?? '');
    }

    public static function check(): bool
    {
        return self::verify((string) ($_POST[self::TOKEN_KEY] ?? ''));
    }

    public static function checkRequest(): bool
    {
        return self::verify(self::requestToken());
    }
}
