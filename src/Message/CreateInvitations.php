<?php declare(strict_types = 1);

namespace App\Message;

class CreateInvitations
{
    private $owner;

    public function __construct(string $owner)
    {
        $this->owner = $owner;
    }

    public function getOwner(): string
    {
        return $this->owner;
    }

    public function getCount(): int
    {
        return 5;
    }
}
