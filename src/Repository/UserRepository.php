<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NoResultException;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findUserByEmail(string $email): User
    {
        $user = $this->findOneBy(['email' => $email]);

        if (null === $user) {
            throw new NoResultException();
        }

        return $user;
    }
}
