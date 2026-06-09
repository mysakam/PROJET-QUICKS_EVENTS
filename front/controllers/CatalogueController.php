<?php

class CatalogueController extends Controller
{
    public function index(): void
    {
        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';

        $this->render('catalogues/index', [
            'lang' => $lang,
            'pageTitle' => $lang === 'fr' ? 'Catalogues - QUICK\'EVENTS' : 'Catalogues - QUICK\'EVENTS',
        ], 'main');
    }
}
