<?php declare(strict_types = 1);

namespace App\Registration;

use App\Entity\Invitation;
use App\Entity\User;
use App\Registration\Exceptions\InvalidInvitationException;
use App\Repository\InvitationRepository;
use Doctrine\ORM\NoResultException;

final class InvitationProvider
{
    private $invitationRepository;

    public function __construct(InvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
    }

    /**
     * @return Invitation[]
     */
    public function getInvitations(User $owner): array
    {
        return $this->invitationRepository->findInvitationsByOwner($owner->getId());
    }

    /**
     * @throws InvalidInvitationException When invite code is not redeemable.
     */
    public function getOpenInvitation(string $code): Invitation
    {
        try {
            return $this->invitationRepository->findOpenInvitationByCode($code);
        } catch (NoResultException $exception) {
            throw new InvalidInvitationException(
                sprintf('Could not find an open invitation matching the code "%s"', $code),
                0,
                $exception
            );
        }
    }

    /**
     * @throws InvalidInvitationException When invite code is not redeemable.
     */
    public function getRedeemedInvitation(string $code): Invitation
    {
        try {
            return $this->invitationRepository->findRedeemedInvitationByCode($code);
        } catch (NoResultException $exception) {
            throw new InvalidInvitationException(
                sprintf('Could not find an open invitation matching the code "%s"', $code),
                0,
                $exception
            );
        }
    }
}
