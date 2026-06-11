<?php
$lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';
$langQuery = '?lang=' . $lang;
?>

<footer class="site-footer">
    <div class="site-footer__inner">
        <div>
            <h2 class="site-footer__title">Réseaux sociaux</h2>
            <div class="site-footer__socials">
                <a href="https://www.facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <span class="site-footer__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <path d="M13.5 22v-8.2h2.8l.5-3.2h-3.3V8.5c0-.9.3-1.6 1.7-1.6h1.8V4c-.3 0-1.4-.1-2.7-.1-2.6 0-4.4 1.6-4.4 4.6v2.1H7.5v3.2h2.4V22h3.6z" />
                        </svg>
                    </span>
                    Facebook
                </a>
                <a href="https://www.instagram.com" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <span class="site-footer__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <path d="M12 7.4A4.6 4.6 0 1 0 12 16.6 4.6 4.6 0 0 0 12 7.4zm0 7.6A3 3 0 1 1 12 9a3 3 0 0 1 0 6zm5.9-7.8a1.1 1.1 0 1 1-2.2 0 1.1 1.1 0 0 1 2.2 0z" />
                            <path d="M12 2.8h3.6c1.2 0 1.8.1 2.3.3.6.2 1 .4 1.5.9s.7.9.9 1.5c.2.5.3 1.1.3 2.3V12c0 1.2-.1 1.8-.3 2.3-.2.6-.4 1-.9 1.5s-.9.7-1.5.9c-.5.2-1.1.3-2.3.3H12c-1.2 0-1.8-.1-2.3-.3-.6-.2-1-.4-1.5-.9s-.7-.9-.9-1.5c-.2-.5-.3-1.1-.3-2.3V7.8c0-1.2.1-1.8.3-2.3.2-.6.4-1 .9-1.5s.9-.7 1.5-.9c.5-.2 1.1-.3 2.3-.3zm0-1.8H8.3c-1.4 0-2.3.1-3 .4a5.9 5.9 0 0 0-2.1 1.3A5.9 5.9 0 0 0 1.9 4.8c-.3.7-.4 1.6-.4 3V12c0 1.4.1 2.3.4 3 .3.8.7 1.5 1.3 2.1a5.9 5.9 0 0 0 2.1 1.3c.7.3 1.6.4 3 .4H12c1.4 0 2.3-.1 3-.4a6 6 0 0 0 3.4-3.4c.3-.7.4-1.6.4-3V7.8c0-1.4-.1-2.3-.4-3A6 6 0 0 0 15 1.4c-.7-.3-1.6-.4-3-.4z" />
                        </svg>
                    </span>
                    Instagram
                </a>
                <a href="https://www.linkedin.com" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <span class="site-footer__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" role="img" focusable="false">
                            <path d="M4.9 8.1A2.1 2.1 0 1 1 4.9 3.9a2.1 2.1 0 0 1 0 4.2zM3 21V9.7h3.8V21H3zm6 0V9.7h3.7v1.5h.1c.5-1 1.8-2 3.8-2 4 0 4.7 2.6 4.7 6V21h-3.8v-5.2c0-1.2 0-2.8-1.7-2.8s-2 1.3-2 2.7V21H9z" />
                        </svg>
                    </span>
                    LinkedIn
                </a>
            </div>
        </div>

        <div>
            <h2 class="site-footer__title">Informations</h2>
            <div class="site-footer__links">
                <a href="<?= route('cug') . $langQuery ?>">Conditions d'utilisation (CUG)</a>
                <a href="tel:0603595028">Téléphone: 0603595028</a>
                <a href="mailto:akamsamy69@gmail.com">Email: akamsamy69@gmail.com</a>
            </div>
        </div>
    </div>
</footer>