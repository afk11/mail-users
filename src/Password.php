<?php

namespace Afk11\MailUsers;


class Password
{
    public function create($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public function verify($password, $hash)
    {
        return password_verify($password, $hash);
    }
}