<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

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

    /**
     * @throws NoResultException When no invite code matches the criteria
     */
    public function findOpenInvitationByCode(string $id): Invitation
    {
        $builder = $this->createQueryBuilder('invitation');

        return $builder
            ->where('invitation.id = :id AND invitation.redeemedAt IS NULL')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
    }
}
