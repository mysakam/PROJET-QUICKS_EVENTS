<?php

class AuthMiddleware
{
    public function handle(): bool
    {
        if (!Auth::check()) {
            redirect(route('login'));
            return false;
        }

        return true;
    }
}
