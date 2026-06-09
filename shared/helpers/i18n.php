<?php

function lang(): string
{
    return ($_GET['lang'] ?? ($_SESSION['lang'] ?? 'fr')) === 'en' ? 'en' : 'fr';
}

function set_lang(): void
{
    $_SESSION['lang'] = lang();
}

function translations(): array
{
    static $cache = [];
    $lang = lang();

    if (!isset($cache[$lang])) {
        $file = __DIR__ . '/../i18n/' . $lang . '.php';
        $cache[$lang] = is_file($file) ? require $file : [];
    }

    return $cache[$lang];
}

function t(string $key, ?string $fallback = null): string
{
    $value = translations();

    foreach (explode('.', $key) as $part) {
        if (!is_array($value) || !array_key_exists($part, $value)) {
            return $fallback ?? $key;
        }
        $value = $value[$part];
    }

    return is_string($value) ? $value : ($fallback ?? $key);
}