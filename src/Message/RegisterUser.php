<?php declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterUser
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    public $inviteCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $plainPassword;
}
