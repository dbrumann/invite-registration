<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\InvitationRedeemed;
use App\Registration\InvitationNotifier;
use App\Registration\InvitationProvider;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotifyInviteOwnerHandler implements MessageHandlerInterface
{
    private $invitationProvider;
    private $notifier;

    public function __construct(InvitationProvider $invitationProvider, InvitationNotifier $notifier)
    {
        $this->invitationProvider = $invitationProvider;
        $this->notifier = $notifier;
    }

    public function __invoke(InvitationRedeemed $invitationRedeemedMessage): void
    {
        $invitation = $this->invitationProvider->getRedeemedInvitation($invitationRedeemedMessage->getInviteCode());

        $this->notifier->notifyInvitingUser($invitation->getOwner());
    }
}
