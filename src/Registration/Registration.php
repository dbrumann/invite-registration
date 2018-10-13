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

    public function register(RegistrationRequest $registration): void
    {
        // Step 1: Check invite code, if it is still redeemable
        try {
            $invitation = $this->invitationRepository->findOpenInvitationByCode($registration->inviteCode);
        } catch (NoResultException $exception) {
            throw new InvalidInvitationException(
                sprintf('Could not find an open invitation matching the code "%s"', $registration->inviteCode),
                0,
                $exception
            );
        }

        // Step 2: Create new user
        $user = new User($registration->email);

        $encodedPassword = $this->passwordEncoder->encodePassword($user, $registration->plainPassword);
        $user->updatePassword($encodedPassword);

        $this->entityManager->persist($user);
        try {
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new EmailAlreadyRegisteredException(
                sprintf('There already is a registered user for email address "%s"', $registration->email),
                0,
                $exception
            );
        }

        // Step 3: Redeem invite code used for registration
        $invitation->redeem($user);
        $this->entityManager->flush();

        // Step 4: Create invite codes for new user
        for ($i = 0; $i < 5; ++$i) {
            $this->entityManager->persist(new Invitation($user));
        }
        $this->entityManager->flush();

        // Step 5: Inform invitation owner of newly registered user
        $message = new Swift_Message(
            'Your invitation was redeemed.',
            'One of your friends registered with an invite code you sent them.'
        );
        $message->setTo([$invitation->getOwner()->getEmail()]);

        $this->mailer->send($message);
    }
}
