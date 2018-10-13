<?php declare(strict_types = 1);

namespace App\Registration;

use App\Dto\Registration as RegistrationRequest;
use App\Entity\Invitation;
use App\Entity\User;
use App\Registration\Exceptions\EmailAlreadyRegisteredException;
use App\Registration\Exceptions\InvalidInvitationException;
use App\Repository\InvitationRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Registration
{
    private $invitationRepository;
    private $passwordEncoder;
    private $entityManager;
    private $mailer;

    public function __construct(
        InvitationRepository $invitationRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        Swift_Mailer $mailer
    ) {
        $this->invitationRepository = $invitationRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    /**
     * @throws InvalidInvitationException When invite code is not redeemable.
     * @throws EmailAlreadyRegisteredException When email is already registered with another user.
     */
    public function register(RegistrationRequest $registration): void
    {
        $invitation = $this->getInvitation($registration->inviteCode);

        $user = $this->registerInvitedUser($registration->email, $registration->plainPassword);

        $this->redeemInvitation($invitation, $user);

        $this->createInviteCodesForInvitedUser($user);

        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new EmailAlreadyRegisteredException(
                sprintf('There already is a registered user for email address "%s"', $registration->email),
                0,
                $exception
            );
        }

        $this->notifyInviteOwner($invitation->getOwner());
    }

    /**
     * Step 1: Check invite code, if it is still redeemable
     */
    private function getInvitation(string $inviteCode): Invitation
    {
        try {
            return $this->invitationRepository->findOpenInvitationByCode($inviteCode);
        } catch (NoResultException $exception) {
            throw new InvalidInvitationException(
                sprintf('Could not find an open invitation matching the code "%s"', $inviteCode),
                0,
                $exception
            );
        }
    }

    /**
     * Step 2: Create new user
     */
    private function registerInvitedUser(string $email, string $password): User
    {
        $user = new User($email);

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $password);
        $user->updatePassword($encodedPassword);

        $this->entityManager->persist($user);

        return $user;
    }

    /**
     * Step 3: Redeem invite code used for registration
     */
    private function redeemInvitation(Invitation $invitation, User $invitedUser): void
    {
        $invitation->redeem($invitedUser);
    }

    /**
     * Step 4: Create invite codes for new user
     */
    private function createInviteCodesForInvitedUser(User $inviteduser): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $this->entityManager->persist(new Invitation($inviteduser));
        }
    }

    /**
     * Step 5: Inform invitation owner of newly registered user
     */
    private function notifyInviteOwner(User $inviteOwner): void
    {
        $message = new Swift_Message(
            'Your invitation was redeemed.',
            'One of your friends registered with an invite code you sent them.'
        );
        $message->setTo([$inviteOwner->getEmail()]);

        $this->mailer->send($message);
    }
}
