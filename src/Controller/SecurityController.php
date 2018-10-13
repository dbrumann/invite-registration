<?php declare(strict_types = 1);

namespace App\Controller;

use App\Dto\Registration;
use App\Form\RegistrationType;
use App\Repository\InvitationRepository;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
    public function register(Request $request, InvitationRepository $invitationRepository)
    {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Registration $registration */
            $registration = $form->getData();

            // Step 1: Check invite code, if it is still redeemable
            if (!$invitationRepository->isRedeemable($registration->inviteCode)) {
                $form->get('inviteCode')->addError(new FormError('The invitation code is not valid.'));

                return $this->render(
                    'register.html.twig',
                    [
                        'form' => $form->createView(),
                    ]
                );
            }

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
