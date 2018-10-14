<?php declare(strict_types = 1);

namespace App\Message;

class RedeemInvitation
{
    private $inviteCode;
    private $invitedUser;

    public function __construct(string $inviteCode, string $invitedUser)
    {
        $this->inviteCode = $inviteCode;
        $this->invitedUser = $invitedUser;
    }

    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }

    public function getInvitedUser(): string
    {
        return $this->invitedUser;
    }
}
