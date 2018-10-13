<?php declare(strict_types = 1);

namespace App\Registration;

use App\Entity\User;
use Swift_Mailer;
use Swift_Message;

final class InvitationNotifier
{
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function notifyInvitingUser(User $user): void
    {
        $message = new Swift_Message(
            'Your invitation was redeemed.',
            'One of your friends registered with an invite code you sent them.'
        );
        $message->setTo([$user->getEmail()]);

        $this->mailer->send($message);
    }
}
