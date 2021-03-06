<?php declare(strict_types = 1);

namespace App\DataFixtures;

use App\Entity\Invitation;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User('denis.brumann@sensiolabs.de');
        $user->updatePassword($this->passwordEncoder->encodePassword($user, 'secret'));

        $manager->persist($user);

        // Create 5 invite codes for our initial user
        for($i = 0; $i < 5; ++$i) {
            $manager->persist(new Invitation($user));
        }

        $manager->flush();
    }
}
