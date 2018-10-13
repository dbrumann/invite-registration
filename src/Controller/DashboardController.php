<?php declare(strict_types = 1);

namespace App\Controller;

use App\Repository\InvitationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    private $invitationRepository;

    public function __construct(InvitationRepository $invitationRepository)
    {
        $this->invitationRepository = $invitationRepository;
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
                'invitations' => $this->invitationRepository->findInvitationsByOwner($this->getUser()),
            ]
        );
    }
}
