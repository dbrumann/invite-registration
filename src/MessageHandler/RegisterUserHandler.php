<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\RegisterUser;
use App\Registration\RegistrationFacade;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RegisterUserHandler implements MessageHandlerInterface
{
    private $registration;

    public function __construct(RegistrationFacade $registration)
    {
        $this->registration = $registration;
    }

    public function __invoke(RegisterUser $registerUserMessage)
    {
        $this->registration->register($registerUserMessage);
    }
}
