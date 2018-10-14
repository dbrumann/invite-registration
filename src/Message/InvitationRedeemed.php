<?php declare(strict_types = 1);

namespace App\Message;

class InvitationRedeemed
{
    private $invitation;

    public function __construct(string $invitation)
    {
        $this->invitation = $invitation;
    }

    public function getInvitation(): string
    {
        return $this->invitation;
    }
}
