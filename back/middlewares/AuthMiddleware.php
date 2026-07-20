<?php

class AuthMiddleware
{
    public function handle(): bool
    {
        if (!Auth::check()) {
            redirect(route('admin_login'));
            return false;
        }

        return true;
    }
}
