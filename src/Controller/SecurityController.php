<?php declare(strict_types = 1);

namespace App\Controller;

use App\Form\RegistrationType;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function register(Request $request)
    {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // TODO: Perform registration and redirect on success

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
