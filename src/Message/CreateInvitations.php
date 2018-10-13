<?php declare(strict_types = 1);

namespace App\Message;

use App\Entity\User;

class CreateInvitations
{
    private $owner;

    public function __construct(User $owner)
    {
        $this->owner = $owner;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function getCount(): int
    {
        return 5;
    }
}
