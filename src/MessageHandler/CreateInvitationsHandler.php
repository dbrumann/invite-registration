<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\CreateInvitations;
use App\Registration\InvitationGenerator;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateInvitationsHandler implements MessageHandlerInterface
{
    private $invitationGenerator;

    public function __construct(InvitationGenerator $invitationGenerator)
    {
        $this->invitationGenerator = $invitationGenerator;
    }

    public function __invoke(CreateInvitations $createInvitationsMessage): void
    {
        $this->invitationGenerator->generateMultiple(
            $createInvitationsMessage->getOwner(),
            $createInvitationsMessage->getCount()
        );
    }
}
