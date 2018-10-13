<?php declare(strict_types = 1);

namespace App\Controller;

use App\Registration\InvitationProvider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $invitationProvider;

    public function __construct(InvitationProvider $invitationProvider)
    {
        $this->invitationProvider = $invitationProvider;
    }

    /**
     * @IsGranted("ROLE_USER")
     * @Route(path="/", name="dashboard")
     */
    public function index()
    {
        return $this->render(
            'dashboard.html.twig',
            [
                'invitations' => $this->invitationProvider->getInvitations($this->getUser()),
            ]
        );
    }
}
