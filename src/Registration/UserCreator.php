<?php declare(strict_types = 1);

namespace App\Registration;

use App\Entity\User;
use App\Registration\Exceptions\EmailAlreadyRegisteredException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserCreator
{
    private $passwordEncoder;
    private $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function create(string $email, string $password): User
    {
        $user = new User($email);

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
        $user->updatePassword($encodedPassword);

        $this->entityManager->persist($user);

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new EmailAlreadyRegisteredException(
                sprintf('There already is a registered user for email address "%s"', $email),
                0,
                $exception
            );
        }

        return $user;
    }
}
