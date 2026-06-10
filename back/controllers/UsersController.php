<?php

class UsersController extends Controller
{
    public function index(): void
    {
        $users = [];

        try {
            $stmt = Database::getPdo()->query('SELECT id_client, nom, prenom, email, telephone FROM clients ORDER BY id_client DESC LIMIT 100');
            $users = $stmt->fetchAll();
        } catch (Throwable) {
            $users = [];
        }

        $this->render('users/index', [
            'pageTitle' => 'Clients',
            'users' => $users,
        ]);
    }
}