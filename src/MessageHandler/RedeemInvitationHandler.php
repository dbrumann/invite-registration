<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\CreateInvitations;
use App\Message\InvitationRedeemed;
use App\Message\RedeemInvitation;
use App\Registration\InvitationProvider;
use App\Registration\InvitationRedeemer;
use App\Registration\UserProvider;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RedeemInvitationHandler implements MessageHandlerInterface
{
    private $invitationProvider;
    private $userProvider;
    private $invitationRedeemer;
    private $messageBus;

    public function __construct(
        InvitationProvider $invitationProvider,
        UserProvider $userProvider,
        InvitationRedeemer $invitationRedeemer,
        MessageBusInterface $messageBus
    ) {
        $this->invitationProvider = $invitationProvider;
        $this->userProvider = $userProvider;
        $this->invitationRedeemer = $invitationRedeemer;
        $this->messageBus = $messageBus;
    }

    public function __invoke(RedeemInvitation $redeemInvitationMessage): void
    {
        $invitation = $this->invitationProvider->getOpenInvitation($redeemInvitationMessage->getInviteCode());
        $inviteduser = $this->userProvider->getUserByEmail($redeemInvitationMessage->getInvitedEmail());

        $this->invitationRedeemer->redeem($invitation, $inviteduser);

        $this->messageBus->dispatch(new CreateInvitations((string) $inviteduser));

        $this->messageBus->dispatch(new InvitationRedeemed((string) $invitation));
    }
}
