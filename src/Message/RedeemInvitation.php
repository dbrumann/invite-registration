<?php declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

class RedeemInvitation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    private $inviteCode;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $invitedEmail;

    public function __construct(string $inviteCode, string $invitedEmail)
    {
        $this->inviteCode = $inviteCode;
        $this->invitedEmail = $invitedEmail;
    }

    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }

    public function getInvitedEmail(): string
    {
        return $this->invitedEmail;
    }
}
