<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\CreateInvitations;
use App\Registration\InvitationGenerator;
use App\Registration\UserProvider;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateInvitationsHandler implements MessageHandlerInterface
{
    private $userProvider;
    private $invitationGenerator;

    public function __construct(UserProvider $userProvider, InvitationGenerator $invitationGenerator)
    {
        $this->userProvider = $userProvider;
        $this->invitationGenerator = $invitationGenerator;
    }

    public function __invoke(CreateInvitations $createInvitationsMessage): void
    {
        $owner = $this->userProvider->getUserByEmail($createInvitationsMessage->getOwnerEmail());

        $this->invitationGenerator->generateMultiple($owner, $createInvitationsMessage->getCount());
    }
}
