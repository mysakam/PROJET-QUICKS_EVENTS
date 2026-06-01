<?php

class AuthMiddleware
{
    public static function handle(): void
    {
        if (empty($_SESSION['client'])) {
            redirect(route('login'));
            exit;
        }
    }
}