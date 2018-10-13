<?php declare(strict_types = 1);

namespace App\Controller;

use App\Dto\Registration;
use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\InvitationRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route(path="/login", name="login")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        return $this->render(
            'login.html.twig',
            [
                'error' => $authUtils->getLastAuthenticationError(),
                'last_username' => $authUtils->getLastUsername(),
            ]
        );
    }

    /**
     * @Route(path="/register", name="register")
     */
    public function register(
        Request $request,
        InvitationRepository $invitationRepository,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager
    ) {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Registration $registration */
            $registration = $form->getData();

            // Step 1: Check invite code, if it is still redeemable
            try {
                $invitation = $invitationRepository->findOpenInvitationByCode($registration->inviteCode);
            } catch (NoResultException $exception) {
                $form->get('inviteCode')->addError(new FormError('The invitation code is not valid.'));

                return $this->render(
                    'register.html.twig',
                    [
                        'form' => $form->createView(),
                    ]
                );
            }

            // Step 2: Create new user
            $user = new User($registration->email);

            $encodedPassword = $passwordEncoder->encodePassword($user, $registration->plainPassword);
            $user->updatePassword($encodedPassword);

            $entityManager->persist($user);
            try {
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $exception) {
                $form->get('email')->addError(new FormError('This email address is already in use. Please login instead.'));

                return $this->render(
                    'register.html.twig',
                    [
                        'form' => $form->createView(),
                    ]
                );
            }

            // Step 3: Redeem invite code used for registration
            $invitation->redeem($user);
            $entityManager->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'register.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route(path="/logout", name="logout")
     */
    public function logout()
    {
        throw new RuntimeException('This route will be handled by Symfony\'s security system.');
    }
}
