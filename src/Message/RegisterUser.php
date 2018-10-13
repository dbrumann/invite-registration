<?php declare(strict_types = 1);

namespace App\Message;

class RegisterUser
{
    public $inviteCode;
    public $email;
    public $plainPassword;
}
