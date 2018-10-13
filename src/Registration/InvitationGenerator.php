<?php declare(strict_types = 1);

namespace App\Registration;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class InvitationGenerator
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateOne(User $owner): void
    {
        $invitation = new Invitation($owner);

        $this->entityManager->persist($invitation);
        $this->entityManager->flush();
    }

    public function generateMultiple(User $owner, int $count): void
    {
        for ($i = 0; $i < $count; ++$i) {
            $invitation = new Invitation($owner);
            $this->entityManager->persist($invitation);
        }

        $this->entityManager->flush();
    }
}
