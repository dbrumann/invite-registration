<?php declare(strict_types = 1);

namespace App\Registration;

use App\Entity\User;
use App\Registration\Exceptions\InvalidEmailException;
use App\Repository\UserRepository;
use Doctrine\ORM\NoResultException;

final class UserProvider
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUserByEmail(string $email): User
    {
        try {
            return $this->userRepository->findUserByEmail($email);
        } catch (NoResultException $exception) {
            throw new InvalidEmailException(
                sprintf('Could not find a registered user for email "%s"', $email),
                0,
                $exception
            );
        }
    }
}
