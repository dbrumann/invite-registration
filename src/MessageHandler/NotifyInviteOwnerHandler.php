<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\InvitationRedeemed;
use App\Registration\InvitationNotifier;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class NotifyInviteOwnerHandler implements MessageHandlerInterface
{
    private $notifier;

    public function __construct(InvitationNotifier $notifier)
    {
        $this->notifier = $notifier;
    }

    public function __invoke(InvitationRedeemed $invitationRedeemedMessage): void
    {
        $this->notifier->notifyInvitingUser($invitationRedeemedMessage->getInvitation()->getOwner());
    }
}
