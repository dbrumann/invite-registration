<?php declare(strict_types = 1);

namespace App\Message;

use App\Entity\User;

class RedeemInvitation
{
    private $inviteCode;
    private $invitedUser;

    public function __construct(string $inviteCode, User $invitedUser)
    {
        $this->inviteCode = $inviteCode;
        $this->invitedUser = $invitedUser;
    }

    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }

    public function getInvitedUser(): User
    {
        return $this->invitedUser;
    }
}
