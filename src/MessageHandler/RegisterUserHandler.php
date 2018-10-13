<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\RedeemInvitation;
use App\Message\RegisterUser;
use App\Registration\UserCreator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterUserHandler implements MessageHandlerInterface
{
    private $userCreator;
    private $messageBus;

    public function __construct(UserCreator $userCreator, MessageBusInterface $messageBus)
    {
        $this->userCreator = $userCreator;
        $this->messageBus = $messageBus;
    }

    public function __invoke(RegisterUser $registerUserMessage): void
    {
        $createdUser = $this->userCreator->create($registerUserMessage->email, $registerUserMessage->plainPassword);

        $this->messageBus->dispatch(new RedeemInvitation($registerUserMessage->inviteCode, $createdUser));
    }
}
