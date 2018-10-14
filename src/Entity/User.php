<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_user")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(unique=true)
     */
    private $email;

    /**
     * @ORM\Column(name="password", length=100)
     */
    private $encodedPassword = '';

    public function __construct(string $email)
    {
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function updatePassword(string $encodedPassword): void
    {
        $this->encodedPassword = $encodedPassword;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function getPassword(): string
    {
        return $this->encodedPassword;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function __toString(): string
    {
        return $this->getEmail();
    }
}
