<?php declare(strict_types = 1);

namespace App\Controller;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route(path="/login", name="login")
     */
    public function login()
    {
        return $this->render('login.html.twig');
    }

    /**
     * @Route(path="/register", name="register")
     */
    public function register()
    {
        return $this->render('register.html.twig');
    }

    /**
     * @Route(path="/logout", name="logout")
     */
    public function logout()
    {
        throw new RuntimeException('This route will be handled by Symfony\'s security system.');
    }
}
