<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home/index');
    }

    public function cgu(): void
    {
        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';

        $this->render('home/cug', [
            'lang' => $lang,
            'pageTitle' => $lang === 'fr' ? 'Conditions générales d\'utilisation (CGU)' : 'Terms and conditions (CGU)',
        ]);
    }

    public function rgpd(): void
    {
        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';

        $this->render('home/rgpd', [
            'lang' => $lang,
            'pageTitle' => $lang === 'fr' ? 'Protection des données (RGPD)' : 'Data protection (GDPR)',
        ]);
    }
}
