<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;

class InvitationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    /**
     * @return Invitation[]
     */
    public function findInvitationsByOwner(int $id): array
    {
        return $this->findBy(['owner' => $id]);
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
