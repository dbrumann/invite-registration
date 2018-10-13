<?php declare(strict_types = 1);

namespace App\Registration;

use App\Dto\Registration as RegistrationRequest;
use App\Registration\Exceptions\EmailAlreadyRegisteredException;
use App\Registration\Exceptions\InvalidInvitationException;
use App\Registration\Exceptions\RegistrationFailedException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use DomainException;

final class RegistrationFacade
{
    private $invitationProvider;
    private $userCreator;
    private $invitationRedeemer;
    private $invitationGenerator;
    private $invitationNotifier;
    private $entityManager;

    public function __construct(
        InvitationProvider $invitationProvider,
        UserCreator $userCreator,
        InvitationRedeemer $invitationRedeemer,
        InvitationGenerator $invitationGenerator,
        InvitationNotifier $invitationNotifier,
        EntityManagerInterface $entityManager
    ) {
        $this->invitationProvider = $invitationProvider;
        $this->userCreator = $userCreator;
        $this->invitationRedeemer = $invitationRedeemer;
        $this->invitationGenerator = $invitationGenerator;
        $this->invitationNotifier = $invitationNotifier;
        $this->entityManager = $entityManager;

    }

    /**
     * @throws InvalidInvitationException When invite code is not redeemable.
     * @throws EmailAlreadyRegisteredException When email is already registered with another user.
     */
    public function register(RegistrationRequest $registration): void
    {
        $this->entityManager->beginTransaction();

        try {
            // Step 1: Check invite code, if it is still redeemable
            $invitation = $this->invitationProvider->getOpenInvitation($registration->inviteCode);

            // Step 2: Create new user
            $user = $this->userCreator->create($registration->email, $registration->plainPassword);

            // Step 3: Redeem invite code used for registration
            $this->invitationRedeemer->redeem($invitation, $user);

            // Step 4: Create invite codes for new user
            $this->invitationGenerator->generateMultiple($user, 5);
        } catch (ORMException $exception) {
            $this->entityManager->rollback();

            throw new RegistrationFailedException('Performing the registration failed', 0, $exception);
        } catch (DomainException $exception) {
            $this->entityManager->rollback();

            throw new RegistrationFailedException($exception->getMessage(), 0, $exception);
        }

        $this->entityManager->commit();

        // Step 5: Inform invitation owner of newly registered user
        $this->invitationNotifier->notifyInvitingUser($invitation->getOwner());
    }
}
