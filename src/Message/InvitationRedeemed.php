<?php declare(strict_types = 1);

namespace App\Message;

use App\Entity\Invitation;

class InvitationRedeemed
{
    private $invitation;

    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function getInvitation(): Invitation
    {
        return $this->invitation;
    }
}
