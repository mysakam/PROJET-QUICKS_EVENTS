<?php

class HomeController extends Controller
{
    public function index(): void
    {
        $lang = ($_GET['lang'] ?? 'fr') === 'en' ? 'en' : 'fr';

        $this->render('home/index', [
            'lang' => $lang,
            'pageTitle' => "QUICK'EVENTS",
        ], 'main');
    }
}