<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    public function findInvitationsByOwner(User $user)
    {
        return $this->findBy(['owner' => $user]);
    }

    public function isRedeemable(string $id): bool
    {
        $builder = $this->createQueryBuilder('invitation');

        return $builder
            ->select('COUNT(invitation)')
            ->where('invitation.id = :id AND invitation.redeemedAt IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult() === 1;
    }
}
