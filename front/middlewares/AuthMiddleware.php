<?php

class AuthMiddleware
{
    public function handle(): bool
    {
        if (empty($_SESSION['client'])) {
            redirect(route('login'));
            return false;
        }

        return true;
    }
}
