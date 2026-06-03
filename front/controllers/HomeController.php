<?php
class HomeController extends Controller //utilise le layout none pour une page d'accueil épurée//
{
    public function index(): void
    {
        $this->render('home/index', [], 'none'); 
    }
}