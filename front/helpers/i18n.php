<?php

/**
 * Centralisation des traductions FR / EN
 * Usage : t('nav.home')  ou  t('nav.home', 'en')
 * La langue courante est lue dans $_GET['lang'] si $lang n'est pas passé.
 */

if (!function_exists('current_lang')) {
    function current_lang(): string
    {
        return (($_GET['lang'] ?? 'fr') === 'en') ? 'en' : 'fr';
    }
}

if (!function_exists('t')) {
    function t(string $key, ?string $lang = null): string
    {
        static $dict = null;
        if ($dict === null) {
            $dict = require __DIR__ . '/../lang/translations.php';
        }
        $lang = $lang ?? current_lang();
        $parts = explode('.', $key);
        $node = $dict[$lang] ?? $dict['fr'] ?? [];
        foreach ($parts as $part) {
            if (!is_array($node) || !array_key_exists($part, $node)) {
                // Fallback : cherche la même clé dans la langue opposée
                $fallback = $dict[$lang === 'fr' ? 'en' : 'fr'] ?? [];
                foreach ($parts as $p) {
                    if (!is_array($fallback) || !array_key_exists($p, $fallback)) {
                        return $key; // clé absente : retourne la clé brute
                    }
                    $fallback = $fallback[$p];
                }
                return is_string($fallback) ? $fallback : $key;
            }
            $node = $node[$part];
        }
        return is_string($node) ? $node : $key;
    }
}

/**
 * Convertit un chemin d'image (absolu /assets/... ou relatif) en URL correcte
 * quelle que soit la profondeur de sous-dossier du projet.
 */
if (!function_exists('img_url')) {
    function img_url(string $path): string
    {
        // Retire le / initial puis délègue à asset()
        return asset(ltrim($path, '/'));
    }
}
