<?php

class AdminMiddleware
{
    public function handle(): bool
    {
        if (!Auth::check()) {
            redirect(route('login'));
            return false;
        }

        if (!Auth::isAdmin()) {
            http_response_code(403);
            echo 'Acces reserve a l\'administrateur.';
            return false;
        }

        return true;
    }
}
