<?php declare(strict_types = 1);

namespace App\Registration;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class InvitationRedeemer
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function redeem(Invitation $invitation, User $invitedUser): void
    {
        $invitation->redeem($invitedUser);

        $this->entityManager->flush();
    }
}
