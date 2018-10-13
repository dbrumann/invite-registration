<?php declare(strict_types = 1);

namespace App\MessageHandler;

use App\Message\CreateInvitations;
use App\Message\InvitationRedeemed;
use App\Message\RedeemInvitation;
use App\Registration\InvitationProvider;
use App\Registration\InvitationRedeemer;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RedeemInvitationHandler implements MessageHandlerInterface
{
    private $invitationProvider;
    private $invitationRedeemer;
    private $messageBus;

    public function __construct(
        InvitationProvider $invitationProvider,
        InvitationRedeemer $invitationRedeemer,
        MessageBusInterface $messageBus
    ) {
        $this->invitationProvider = $invitationProvider;
        $this->invitationRedeemer = $invitationRedeemer;
        $this->messageBus = $messageBus;
    }

    public function __invoke(RedeemInvitation $redeemInvitationMessage): void
    {
        $invitation = $this->invitationProvider->getOpenInvitation($redeemInvitationMessage->getInviteCode());
        $this->invitationRedeemer->redeem($invitation, $redeemInvitationMessage->getInvitedUser());

        $this->messageBus->dispatch(new CreateInvitations($redeemInvitationMessage->getInvitedUser()));

        $this->messageBus->dispatch(new InvitationRedeemed($invitation));
    }
}
