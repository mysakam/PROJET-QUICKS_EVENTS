<?php
class AuthMiddleware 

{ public function handle() : bool 
{  
    if (empty($_SESSION[ 'user'])) { 
        header('Location: /login'); 
        return false; 
    }
    return true;
}

}