<?php declare(strict_types = 1);

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

class CreateInvitations
{
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $ownerEmail;

    public function __construct(string $ownerEmail)
    {
        $this->ownerEmail = $ownerEmail;
    }

    public function getOwnerEmail(): string
    {
        return $this->ownerEmail;
    }

    public function getCount(): int
    {
        return 5;
    }
}
