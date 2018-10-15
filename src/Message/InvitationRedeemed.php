<?php declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

class InvitationRedeemed
{
    /**
     * @Assert\NotBlank()
     * @Assert\Uuid()
     */
    private $inviteCode;

    public function __construct(string $inviteCode)
    {
        $this->inviteCode = $inviteCode;
    }

    public function getInviteCode(): string
    {
        return $this->inviteCode;
    }
}
