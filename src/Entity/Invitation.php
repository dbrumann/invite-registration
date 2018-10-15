<?php declare(strict_types = 1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Entity()
 * @ORM\Table(name="app_invite")
 */
class Invitation
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    private $inviteCode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", cascade={"all"})
     * @ORM\JoinColumn(name="owner_id", nullable=false)
     */
    private $owner;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $redeemedAt;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $redeemedBy;

    public function __construct(User $owner)
    {
        $this->inviteCode = Uuid::uuid4();
        $this->owner = $owner;
    }

    public function redeem(User $invitee): void
    {
        $this->redeemedAt = new DateTimeImmutable();
        $this->redeemedBy = $invitee->getEmail();
    }

    public function getInviteCode(): string
    {
        return $this->inviteCode->toString();
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function isRedeemed(): bool
    {
        return $this->redeemedAt !== null;
    }

    public function __toString(): string
    {
        return $this->getInviteCode();
    }
}
