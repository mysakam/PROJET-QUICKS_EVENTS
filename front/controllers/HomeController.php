<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $this->render('home/index');
    }

    public function cug(): void
    {
        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';

        $this->render('home/cug', [
            'lang' => $lang,
            'pageTitle' => $lang === 'fr' ? 'Conditions d\'utilisation (CUG)' : 'Terms of use (CUG)',
        ]);
    }
}
